<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PartnerController;
use App\Models\Lapangan;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (CUSTOMER & GUEST)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $lapangan = Lapangan::latest()->take(6)->get(); 
    return view('welcome', compact('lapangan'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');

// ✅ LAPANGAN UNTUK CUSTOMER/GUEST
Route::get('/lapangan', [LapanganController::class, 'customerIndex'])->name('lapangan.index');
Route::get('/lapangan/{lapangan}', [LapanganController::class, 'customerShow'])->name('lapangan.show');

/*
|--------------------------------------------------------------------------
| CART ROUTES (SEMUA USER)
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

/*
|--------------------------------------------------------------------------
| AUTH ONLY (CUSTOMER & PARTNER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // DAFTAR MITRA
    Route::get('/daftar-mitra', [ProfileController::class, 'partnerForm'])->name('partner.form');
    Route::post('/daftar-mitra', [ProfileController::class, 'registerPartner'])->name('partner.register');

    // BOOKING
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/lapangan/{lapangan}/order-now', [BookingController::class, 'orderNow'])->name('order-now');
        Route::post('/lapangan/{lapangan}/order-now', [BookingController::class, 'storeOrderNow'])->name('order-now.store');
        Route::get('/from-cart', [BookingController::class, 'createFromCart'])->name('cart');
        Route::post('/from-cart', [BookingController::class, 'storeFromCart'])->name('cart.store');
        Route::get('/{booking}/checkout', [BookingController::class, 'checkout'])->name('checkout');
        Route::post('/{booking}/confirm', [BookingController::class, 'confirm'])->name('confirm');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::get('/{booking}/receipt', [BookingController::class, 'receipt'])->name('receipt');
    });

    /*
    |--------------------------------------------------------------------------
    | PARTNER ONLY — HANYA LAPANGAN (SESUAI STRUKTUR ANDA)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:partner')->prefix('partner')->name('partner.')->group(function () {
        // Dashboard partner
        Route::get('/dashboard', [PartnerController::class, 'dashboard'])->name('dashboard');
        Route::post('/leave', [ProfileController::class, 'leavePartner'])->name('leave');

        // Manage Product
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'manage'])->name('manage');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });

        // Manage Voucher
        Route::prefix('vouchers')->name('vouchers.')->group(function () {
            Route::get('/create', [VoucherController::class, 'create'])->name('create');
            Route::post('/', [VoucherController::class, 'store'])->name('store');
            Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
            Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
            Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
        });

        // ✅ MANAGE LAPANGAN — SESUAI FOLDER ANDA: lapangan/partner
        Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
        Route::get('/lapangan/create', [LapanganController::class, 'create'])->name('lapangan.create');
        Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');
        Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('lapangan.edit');
        Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('lapangan.update');
        Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN (SUPER ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('template.dashboard'))->name('dashboard');
    });
});

require __DIR__.'/auth.php';