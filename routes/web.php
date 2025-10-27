<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as FrontProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Auth\ProviderController;
use Illuminate\Support\Facades\Route;

// Homepage - accessible by everyone
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product Routes
Route::get('/products/{id}', [FrontProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [FrontProductController::class, 'category'])->name('products.category');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

// Social Login Routes
Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect'])
    ->name('social.redirect');
    
Route::get('/auth/{provider}/callback', [ProviderController::class, 'callback'])
    ->name('social.callback');

// Customer Dashboard (after login)
Route::get('/dashboard', function () {
    // Redirect admin to admin dashboard
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Customer sees homepage
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Customer Orders
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/my-orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('customer.orders.cancel');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Inventory Management (Quản lý kho)
    Route::resource('inventory', InventoryController::class);
    Route::get('inventory/attributes/{category_id}', [InventoryController::class, 'getAttributesByCategory'])
        ->name('inventory.attributes');
    
    // Category Management (Quản lý danh mục)
    Route::resource('categories', CategoryController::class);
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])
        ->name('categories.update-order');
    
    // Product Management (Quản lý sản phẩm)
    Route::resource('products', ProductController::class);
    Route::post('products/{id}/publish', [ProductController::class, 'publish'])
        ->name('products.publish');
    Route::post('products/{id}/unpublish', [ProductController::class, 'unpublish'])
        ->name('products.unpublish');
    
    // Slider Management (Quản lý slider)
    Route::resource('sliders', SliderController::class);
    
    // Order Management (Quản lý đơn hàng)
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store', 'destroy']);
    Route::patch('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

require __DIR__.'/auth.php';
