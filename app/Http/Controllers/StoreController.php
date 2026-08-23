<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\RepairOrder;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');

        $query = Product::with('category')->where('is_active', true);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('store.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    /**
     * US-011: Consulta pública de estado de reparación por código de seguimiento.
     */
    public function trackRepair(Request $request)
    {
        $code = trim($request->input('code'));
        $repair = null;

        if ($code) {
            $repair = RepairOrder::with('technician')
                ->where('tracking_code', $code)
                ->first();
        }

        return view('store.track-repair', compact('repair', 'code'));
    }
}
