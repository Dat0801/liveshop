<?php

use App\Livewire\ProductList;
use App\Livewire\ProductDetail;
use App\Livewire\CartPage;
use App\Livewire\CheckoutForm;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ProductManagement;
use App\Livewire\Admin\OrderManagement;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Homepage
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Product Routes
Route::get('/products', ProductList::class)->name('products.index');
Route::get('/products/{product:slug}', ProductDetail::class)->name('products.show');

// Cart Route
Route::get('/cart', CartPage::class)->name('cart.index');

// Checkout Route
Route::get('/checkout', CheckoutForm::class)
    ->middleware('auth')
    ->name('checkout');

// Order Success Route
Route::get('/order/success/{order}', function ($order) {
    return view('order-success', ['orderId' => $order]);
})->name('order.success');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/products', ProductManagement::class)->name('admin.products');
    Route::get('/orders', OrderManagement::class)->name('admin.orders');
});

require __DIR__.'/auth.php';
