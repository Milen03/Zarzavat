<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartContoller extends Controller
{
    public function index(){
        $cart = session()->get('cart',[]);
        return view('cart.index', compact('cart'));
    }

    //add 
    public function add(Request $request,$id){
        $product = Product::findOrFail($id);

        $cart = session()->get('cart',[]);

        if(isset($cart[$id])){
            $cart[$id]['quantity']++;
        } else{

            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image ?? null
            ];
        }

        session()->put('cart',$cart);
        
        return redirect()->route('cart.index')->wiht('success','Продуктът е добавен в количката!');

    }

    //update

    public function update(Request $request,$id)
    {
        $cart = session()->get('cart',[]);

        if(isset($cart[$id])){
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart',$cart);
        }

        return redirect()->route('cart.index');
    }


    //remove
    public function remove(Request $request,$id)
    {
        $cart = session()->get('cart',[]);

        if(isset($cart[$id])){
            unset($cart[$id]);
            session()->put('cart',$cart);
        }

        return redirect()->route('cart.index')->with('success','Продуктът е премахнат!');

    }
}
