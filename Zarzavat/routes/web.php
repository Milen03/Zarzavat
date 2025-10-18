<?php

use App\Http\Controllers\CartContoller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

//auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\Admin\AdminContoller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

use App\Http\Controllers\ProfileController;


//Auth
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

//Admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminContoller::class, 'index'])->name('dashboard');
    
    // Продукти
    Route::resource('products', AdminProductController::class);

   // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update.status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
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

// Маршрути за профил/поръчки (работят и за логнати и за гости)
Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/orders/{orderId}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/order-items/{itemId}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/order-items/{itemId}', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});