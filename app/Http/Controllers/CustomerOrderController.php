<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    /**
     * Muestra el historial de compras y pedidos del cliente autenticado.
     */
    public function myOrders()
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();

        $orders = collect();
        if ($client) {
            $orders = Sale::where('client_id', $client->id)
                ->with('details.product')
                ->latest()
                ->paginate(10);
        }

        return view('store.my-orders', compact('orders'));
    }

    /**
     * Procesa la compra / reserva directa de un producto desde el catálogo.
     */
    public function checkoutProduct(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'payment_method' => 'required|string',
            'delivery_notes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Obtener o crear el perfil comercial del cliente vinculado a su usuario
        $client = Client::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        );

        try {
            $sale = DB::transaction(function () use ($product, $request, $user, $client) {
                $lockedProduct = Product::lockForUpdate()->findOrFail($product->id);

                if ($lockedProduct->stock < $request->quantity) {
                    throw new \Exception("Stock insuficiente para este producto.");
                }

                $lastSale = Sale::latest('id')->first();
                $nextNumber = $lastSale ? $lastSale->id + 1 : 1;
                $voucherNumber = 'WEB-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

                $total = $lockedProduct->price * $request->quantity;

                $sale = Sale::create([
                    'user_id' => 1, // Asignado al administrador del sistema
                    'client_id' => $client->id,
                    'voucher_number' => $voucherNumber,
                    'total' => $total,
                    'payment_method' => $request->payment_method,
                    'notes' => 'Pedido Web de Cliente. ' . ($request->delivery_notes ?? ''),
                ]);

                // Descontar inventario
                $lockedProduct->decrement('stock', $request->quantity);

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $lockedProduct->id,
                    'quantity' => $request->quantity,
                    'price' => $lockedProduct->price,
                    'subtotal' => $total,
                ]);

                return $sale;
            });

            return redirect()->route('customer.orders')->with('success', '¡Compra realizada con éxito! Tu número de orden es: ' . $sale->voucher_number);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
}
