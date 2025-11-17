<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartController;
use App\Models\Lapangan;

// ===============================
// HALAMAN UTAMA (SEMUA ORANG)
// ===============================
Route::get('/', function () {
    $lapangans = Lapangan::all();
    return view('welcome', compact('lapangans'));
})->name('home');

// Produk & Voucher (publik)
 Route::resource('vouchers', \App\Http\Controllers\VoucherController::class);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');



// ===============================
// AUTHENTICATED USER (LOGIN)
// ===============================
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daftar lapangan
    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');

    // Daftar jadi mitra
    Route::get('/daftar-mitra', [ProfileController::class, 'partnerForm'])->name('partner.form');
    Route::post('/daftar-mitra', [ProfileController::class, 'registerPartner'])->name('partner.register');

    // BOOKING ROUTES — DIPINDAH KE SINI (TANPA DUPLIKASI)
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/lapangan/{lapanganId}/order-now', [BookingController::class, 'orderNow'])->name('order-now');
        Route::post('/lapangan/{lapanganId}/order-now', [BookingController::class, 'storeOrderNow'])->name('store-order-now');
        Route::get('/from-cart', [BookingController::class, 'createFromCart'])->name('from-cart');
        Route::post('/from-cart', [BookingController::class, 'storeFromCart'])->name('store-from-cart');
        Route::get('/{booking}/checkout', [BookingController::class, 'checkout'])->name('checkout');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::post('/{booking}/confirm', [BookingController::class, 'confirm'])->name('confirm');
    });

    // Produk — hanya partner
    Route::prefix('partner/products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'manage'])->name('manage');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Voucher
    Route::post('/voucher/{voucher}/use', [VoucherController::class, 'use'])->name('vouchers.use');
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
    });
});

// ===============================
// PARTNER & SUPER ADMIN ONLY
// ===============================
Route::middleware(['auth', 'role:super_admin,partner'])->prefix('admin')->group(function () {
    Route::prefix('lapangan')->name('lapangan.')->group(function () {
        Route::get('/create', [LapanganController::class, 'create'])->name('create');
        Route::post('/', [LapanganController::class, 'store'])->name('store');
        Route::get('/{lapangan}/edit', [LapanganController::class, 'edit'])->name('edit');
        Route::put('/{lapangan}', [LapanganController::class, 'update'])->name('update');
        Route::delete('/{lapangan}', [LapanganController::class, 'destroy'])->name('destroy');
    });
});

// ===============================
// CART (SEMUA ORANG)
// ===============================
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
});

require __DIR__ . '/auth.php';