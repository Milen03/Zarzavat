<?php

use App\Http\Controllers\Admin\AdminContoller;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\LoginController;
// auth
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartContoller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Profile\OrderItemController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Auth
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminContoller::class, 'index'])->name('dashboard');

    // Продукти
    Route::resource('products', AdminProductController::class);

    // Поръчки
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update.status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Архив (soft deletes)
    Route::patch('/orders/{id}/restore', [AdminOrderController::class, 'restore'])->name('orders.restore');
    Route::delete('/orders/{id}/force', [AdminOrderController::class, 'forceDelete'])->name('orders.force');
});

// products
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartContoller::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartContoller::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartContoller::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartContoller::class, 'remove'])->name('cart.remove');

// checkout
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');

// Profile
Route::prefix('profile')->group(function () {
    // Основни маршрути за профил
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/orders/{orderId}/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Маршрути за артикули в поръчката
    Route::patch('/order-items/{itemId}', [OrderItemController::class, 'update'])->name('profile.update');
    Route::delete('/order-items/{itemId}', [OrderItemController::class, 'destroy'])->name('profile.destroy');
    Route::get('/orders/{orderId}/add-item', [OrderItemController::class, 'create'])->name('profile.add-item.form');
    Route::post('/orders/{orderId}/add-item', [OrderItemController::class, 'store'])->name('profile.add-item');
});
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// TEMPORARY HEALTH ENDPOINT FOR PRODUCTION DEBUG (REMOVE AFTER FIX)
Route::get('/health', function () {
    try {
        $pdo = DB::connection()->getPdo();
        $userRow = DB::select('select current_user');
        return response()->json([
            'status' => 'ok',
            'env' => config('app.env'),
            'debug' => config('app.debug'),
            'db' => DB::connection()->getDatabaseName(),
            'db_user' => $userRow[0]->current_user ?? null,
            'time' => now()->toISOString(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('health');
