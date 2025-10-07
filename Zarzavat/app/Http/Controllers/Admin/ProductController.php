<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller{
     // Списък продукти
    public function index(){
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    //Форма за създаване 
    public function create(){
    $categories = \App\Models\Category::all();
    return view('admin.products.create', compact('categories'));
}

    //Запис на нов продукт
    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->except('image');
    
    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
        $data['image'] = $imagePath;
    }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Продуктът е добавен.');
    }

    //edit
    public function edit(Product $product){
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

     public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->except('image');
    
    // Handle image upload
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