<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Show all product

    public function index(){
        $products = Product::orederBy('created_at','desc')->paginate(12);
    }

    //show one Product
    public function show($id){

        $products = Product::findeOrFail($id);
        return view('products.show',compact('product'));
    }
}
