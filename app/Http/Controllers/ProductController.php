<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 📋 LISTAR PRODUCTOS
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // ➕ CREAR PRODUCTO (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Producto creado correctamente',
            'product' => $product->load('category')
        ]);
    }

    // ✏️ ACTUALIZAR PRODUCTO (AJAX)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0'
        ]);

        $product->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Producto actualizado correctamente'
        ]);
    }

    // ❌ ELIMINAR PRODUCTO (AJAX)
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Producto eliminado correctamente'
        ]);
    }
}