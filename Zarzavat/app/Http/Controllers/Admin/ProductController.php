<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view('admin.products.create');
    }

    //Запис на нов продукт
    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|string',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Продуктът е добавен.');
    }

    // Изтриване
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Продуктът е изтрит.');
    }
}