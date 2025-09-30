<?php

use App\Http\Controllers\CartContoller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;


Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart' , [CartContoller::class,'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartContoller::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartContoller::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartContoller::class, 'remove'])->name('cart.remove');
Route::get('/checkout',[OrderController::class , 'checkout'])->name('checkout');
Route::post('/checkout' , [OrderController::class , 'placeOrder'])->name('checkout.place');