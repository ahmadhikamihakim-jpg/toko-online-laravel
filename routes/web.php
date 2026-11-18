<?php
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\PaymentController;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::post('/newsletter', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    return back()->with('success', 'Terima kasih sudah berlangganan buletin kami!');
})->name('newsletter');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/sukses/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/bayar/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/pesanan-saya', [CheckoutController::class, 'myOrders'])->name('orders.index');
    Route::get('/pesanan-saya/{order}', [CheckoutController::class, 'myOrderShow'])->name('orders.show');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', AdminCategoryController::class)->except(['show'])->parameters(['kategori'=>'category'])->names('categories');
    Route::resource('produk', AdminProductController::class)->except(['show'])->parameters(['produk'=>'product'])->names('products');
    Route::get('pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('pengguna', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('profil', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profil', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profil/kata-sandi', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('pesanan/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('pesanan/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
});

// Webhook notifikasi Midtrans (tanpa login & tanpa CSRF, diverifikasi lewat signature).
Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])->name('midtrans.notification');
