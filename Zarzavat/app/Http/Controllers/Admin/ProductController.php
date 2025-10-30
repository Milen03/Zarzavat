<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    // Списък продукти
    public function index()
    {
        $products = Product::all();

        return view('admin.products.index', compact('products'));
    }

    // Форма за създаване
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    // Запис на нов продукт
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        // Обработка на изображението
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Продуктът е добавен.');
    }

    // Форма за редакция
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Обновяване на продукта
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Обработка на изображението
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Продуктът е обновен.');
    }

    // Изтриване
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Продуктът е изтрит.');
    }
}
