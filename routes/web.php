<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PaymentController; // ✅ Tambahkan ini
use App\Models\Lapangan;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (CUSTOMER & GUEST)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $lapangans = Lapangan::where('status', 'aktif')->latest()->take(6)->get();
    return view('welcome', compact('lapangans'));
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

    // ✅ PAYMENT ROUTES — DI LUAR GROUP LAIN (TANPA DUPLIKASI)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/lapangan/{lapanganId}/pay', [PaymentController::class, 'create'])->name('create');
        Route::post('/lapangan/{lapanganId}/pay', [PaymentController::class, 'store'])->name('store');
        Route::get('/booking/{booking}/process', [PaymentController::class, 'process'])->name('process');
        Route::post('/notification', [PaymentController::class, 'notification'])->name('notification');
        Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
        Route::get('/error', [PaymentController::class, 'error'])->name('error');
    });

    Route::middleware(['auth'])->group(function () {
    // ✅ Booking routes
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/lapangan/{lapangan}/order-now', [BookingController::class, 'orderNow'])->name('order-now');
        Route::post('/lapangan/{lapangan}/order-now', [BookingController::class, 'storeOrderNow'])->name('store-order-now');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::get('/{booking}/checkout', [BookingController::class, 'checkout'])->name('checkout');
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });
    });
    /*
    |--------------------------------------------------------------------------
    | PARTNER ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:partner,super_admin')->prefix('partner')->name('partner.')->group(function () {
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

        // Manage Lapangan
        Route::prefix('lapangan')->name('lapangan.')->group(function () {
            Route::get('/', [LapanganController::class, 'index'])->name('index');
            Route::get('/create', [LapanganController::class, 'create'])->name('create');
            Route::post('/', [LapanganController::class, 'store'])->name('store');
            Route::get('/{lapangan}/edit', [LapanganController::class, 'edit'])->name('edit');
            Route::put('/{lapangan}', [LapanganController::class, 'update'])->name('update');
            Route::delete('/{lapangan}', [LapanganController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    });
});

// ✅ CALLBACK MIDTRANS (PUBLIC — KARENA DIPANGGIL OLEH MIDTRANS SERVER)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

require __DIR__.'/auth.php';