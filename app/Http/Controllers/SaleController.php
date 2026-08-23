<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * US-007: Listado de ventas registradas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Sale::with(['client', 'user'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('voucher_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQ) use ($search) {
                      $clientQ->where('name', 'like', "%{$search}%")
                              ->orWhere('ci_nit', 'like', "%{$search}%");
                  });
            });
        }

        $sales = $query->paginate(15)->withQueryString();

        return view('sales.index', compact('sales', 'search'));
    }

    /**
     * Muestra la interfaz de punto de venta (POS) para crear una venta.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::where('is_active', true)->where('stock', '>', 0)->orderBy('name')->get();

        // Generar número correlativo de comprobante: VNT-000001
        $lastSale = Sale::latest('id')->first();
        $nextNumber = $lastSale ? $lastSale->id + 1 : 1;
        $voucherNumber = 'VNT-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return view('sales.create', compact('clients', 'products', 'voucherNumber'));
    }

    /**
     * US-006: Registrar la venta con transacción de base de datos y reducción de stock.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_number' => 'required|string|unique:sales,voucher_number',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:150',
            'payment_method' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $clientId = $request->client_id;

                // Si el cliente no existe en la lista y escribió un nombre, lo registramos al vuelo
                if (!$clientId && $request->filled('client_name')) {
                    $newClient = Client::create([
                        'name' => $request->client_name,
                        'ci_nit' => $request->client_ci,
                        'phone' => $request->client_phone,
                    ]);
                    $clientId = $newClient->id;
                }

                $total = 0;
                foreach ($request->products as $item) {
                    $total += $item['quantity'] * $item['price'];
                }

                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'client_id' => $clientId,
                    'voucher_number' => $request->voucher_number,
                    'total' => $total,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                ]);

                foreach ($request->products as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para el producto: {$product->name}. Stock disponible: {$product->stock}");
                    }

                    // Descontar inventario
                    $product->decrement('stock', $item['quantity']);

                    // Crear detalle de línea
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ]);
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale)->with('success', 'Venta registrada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al procesar la venta: ' . $e->getMessage());
        }
    }

    /**
     * US-008: Mostrar detalle y comprobante/recibo de venta.
     */
    public function show(Sale $sale)
    {
        $sale->load(['client', 'user', 'details.product']);
        return view('sales.show', compact('sale'));
    }
}
