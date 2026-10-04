<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        $lowStock = Product::whereColumn('quantity', '<=', 'alert_threshold')->count();

        return view('products.index', compact('products', 'lowStock'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'quantity' => ['required', 'integer', 'min:0'],
            'alert_threshold' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created ✅');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'quantity' => ['required', 'integer', 'min:0'],
            'alert_threshold' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated ✅');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted 🗑️');
    }
}
