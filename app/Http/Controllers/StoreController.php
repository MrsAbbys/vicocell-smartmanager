<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Muestra el catálogo digital público para los clientes.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');

        $query = Product::where('is_active', true)->with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::has('products')->orderBy('name')->get();

        return view('store.index', compact('products', 'categories', 'search', 'categoryId'));
    }
}
