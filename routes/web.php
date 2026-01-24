<?php

use App\Livewire\HomePage;
use App\Livewire\ProductList;
use App\Livewire\ProductDetail;
use App\Livewire\CartPage;
use App\Livewire\Checkout;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ProductManagement;
use App\Livewire\Admin\OrderManagement;
use App\Livewire\Admin\CategoryManagement;
use App\Livewire\Admin\VariantManagement;
use App\Livewire\Admin\MediaManager;
use App\Livewire\Admin\CouponManagement;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\StockAdjustments;
use App\Livewire\Admin\ShippingMethods;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\User\Dashboard as UserDashboard;
use App\Livewire\User\Profile;
use App\Livewire\User\ChangePassword;
use App\Livewire\User\ManageAddresses;
use App\Livewire\User\OrderHistory;
use App\Livewire\User\OrderDetail;
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
Route::get('/', HomePage::class)->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', VerifyEmail::class)->name('verification.notice');
});

// User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', UserDashboard::class)->name('profile');
    Route::get('/profile/settings', Profile::class)->name('profile.settings');
    Route::get('/profile/change-password', ChangePassword::class)->name('profile.change-password');
    Route::get('/profile/addresses', ManageAddresses::class)->name('profile.addresses');
    Route::get('/profile/orders', OrderHistory::class)->name('profile.orders');
    Route::get('/order/{order}', OrderDetail::class)->name('order.detail');
});

// Product Routes
Route::get('/products', ProductList::class)->name('products.index');
Route::get('/products/{product:slug}', ProductDetail::class)->name('products.show');

// Cart Route
Route::get('/cart', CartPage::class)->name('cart.index');

// Checkout Route
Route::get('/checkout', Checkout::class)
    ->middleware('auth')
    ->name('checkout');

// Order Success Route
Route::get('/order/success/{order}', function ($order) {
    return view('order-success', ['orderId' => $order]);
})->name('order.success');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/categories', CategoryManagement::class)->name('admin.categories');
    Route::get('/products', ProductManagement::class)->name('admin.products');
    Route::get('/products/{product}/variants', VariantManagement::class)->name('admin.products.variants');
    Route::get('/products/{product}/media', MediaManager::class)->name('admin.products.media');
    Route::get('/coupons', CouponManagement::class)->name('admin.coupons');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/inventory/adjustments', StockAdjustments::class)->name('admin.stock.adjustments');
    Route::get('/shipping/methods', ShippingMethods::class)->name('admin.shipping.methods');
    Route::get('/orders', OrderManagement::class)->name('admin.orders');
});

require __DIR__.'/auth.php';

