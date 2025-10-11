<?php

use App\Http\Controllers\CartContoller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\AdminContoller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


//Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminContoller::class, 'index'])->name('dashboard');
    
    // Продукти
    Route::resource('products', AdminProductController::class);

    // Поръчки
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
});


//products
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


//Cart
Route::get('/cart' , [CartContoller::class,'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartContoller::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartContoller::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartContoller::class, 'remove'])->name('cart.remove');

//checkout
Route::get('/checkout',[OrderController::class , 'checkout'])->name('checkout');
Route::post('/checkout' , [OrderController::class , 'placeOrder'])->name('checkout.place');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});