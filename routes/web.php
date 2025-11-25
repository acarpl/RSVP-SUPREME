<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HistoryController;
use App\Models\Lapangan;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (GUEST & CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $lapangans = Lapangan::where('status', 'aktif')->latest()->take(6)->get();
    return view('welcome', compact('lapangans'));
})->name('home');

// Produk & Voucher
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
Route::get('/alat', [ProductController::class, 'alat'])->name('products.alat');
Route::get('/makanan', [ProductController::class, 'makanan'])->name('products.makanan');
Route::get('/merchandise', [ProductController::class, 'merchandise'])->name('products.merchandise');

// Lapangan (publik)
Route::get('/lapangan', [LapanganController::class, 'customerIndex'])->name('lapangan.index');
Route::get('/lapangan/{lapangan}', [LapanganController::class, 'customerShow'])->name('lapangan.show');

// 🔑 WEBHOOK MIDTRANS — HARUS DI LUAR SEMUA MIDDLEWARE
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| CART ROUTES (SEMUA USER — DIPERLUKAN AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{productId}', [CartController::class, 'add'])->name('add');
    Route::post('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

/*
|--------------------------------------------------------------------------
| AUTH REQUIRED (CUSTOMER & PARTNER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () { 

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daftar Mitra
    Route::get('/daftar-mitra', [ProfileController::class, 'partnerForm'])->name('partner.form');
    Route::post('/daftar-mitra', [ProfileController::class, 'registerPartner'])->name('partner.register');

    // ✅ BOOKING (CUSTOMER)
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/lapangan/{lapangan}/order-now', [BookingController::class, 'orderNow'])->name('order-now');
        Route::post('/lapangan/{lapangan}/order-now', [BookingController::class, 'storeOrderNow'])->name('store-order-now');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::get('/{booking}/checkout', [BookingController::class, 'checkout'])->name('checkout');
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });

    // ✅ PEMBAYARAN (SNAP + FINISH)
    Route::prefix('payment')->name('payment.')->group(function () {
        // Langkah 1: Form booking → simpan booking
        Route::get('/lapangan/{lapanganId}/order', [PaymentController::class, 'create'])
            ->name('create');

        Route::post('/lapangan/{lapanganId}/order', [PaymentController::class, 'store'])
            ->name('store');

        // Langkah 2: Redirect ke Midtrans
        Route::get('/redirect/{booking}', [PaymentController::class, 'redirect'])
            ->name('redirect');

        // Langkah 3: Setelah bayar → update status
        Route::get('/finish/{booking}', [PaymentController::class, 'finish'])
            ->name('finish');

        Route::get('/error', [PaymentController::class, 'error'])
            ->name('error');

        Route::get('/payment/process/{booking}', [PaymentController::class, 'process'])
            ->name('process');
        
    });
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
    });
    // Riwayat
    Route::get('/riwayat', [HistoryController::class, 'index'])
    ->name('riwayat.index');
});

// Order (produk & sewa alat)
Route::prefix('order')->name('order.')->group(function () {
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{order}/payment', [OrderController::class, 'payment'])->name('payment');
    Route::get('/{order}/finish', [PaymentController::class, 'finishOrder'])->name('finish');
    Route::get('/error', [PaymentController::class, 'orderError'])->name('error');
    Route::get('/{order}/success', [PaymentController::class, 'orderSuccess'])->name('success');
});

/*
|--------------------------------------------------------------------------
| PARTNER ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:partner,super_admin'])
    ->prefix('partner')
    ->name('partner.')
    ->group(function () {

    Route::get('/dashboard', [PartnerController::class, 'dashboard'])->name('dashboard');
    Route::post('/leave', [ProfileController::class, 'leavePartner'])->name('leave');

    // Produk
   Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'manage'])->name('manage');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Voucher
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
    });

    // Lapangan
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
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    });

// Order detail
Route::middleware(['auth'])->prefix('order')->name('order.')->group(function () {
    Route::get('/{order}', [CheckoutController::class, 'show'])->name('show');
});
require __DIR__ . '/auth.php';