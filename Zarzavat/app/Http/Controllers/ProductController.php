<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // Show all product

    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(12);

        return view('products.index', compact('products'));
    }

    // show one Product
    public function show($id)
    {

        $products = Product::findOrFail($id);

        return view('products.show', compact('products'));
    }
}
