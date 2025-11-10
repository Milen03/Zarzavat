<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    // Show all product

    public function index() : View
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(12);

        return view('products.index', compact('products'));
    }

    // show one Product
    public function show(int $id) : View
    {

        $products = Product::findOrFail($id);

        return view('products.show', compact('products'));
    }
}
