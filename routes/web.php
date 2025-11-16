<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Models\Lapangan;
use App\Http\Controllers\ProductController;


// ===============================
// HALAMAN UTAMA
// ===============================
Route::get('/', function () {
    $lapangans = Lapangan::all();
    return view('welcome', compact('lapangans'));
})->name('home');


// ===============================
// PRODUK UNTUK USER (TANPA LOGIN)
// ===============================
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/produk', function () {
    return view('produk.kategori');
})->name('produk.kategori');

Route::get('/produk/alat', [ProductController::class, 'alat'])->name('produk.alat');
Route::get('/produk/makanan', [ProductController::class, 'makanan'])->name('produk.makanan');


// ===============================
// AUTH USER ROUTES
// ===============================
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{id}', [BookingController::class, 'store'])->name('booking.store');

    // Lihat daftar lapangan
    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');

    // Daftar mitra
    Route::get('/daftar-mitra', [ProfileController::class, 'partnerForm'])->name('partner.form');
    Route::post('/daftar-mitra', [ProfileController::class, 'registerPartner'])->name('partner.register');

    // Produk manage partner
    Route::get('/partner/products', [ProductController::class, 'manage'])->name('products.manage');
    Route::get('/partner/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/partner/products', [ProductController::class, 'store'])->name('products.store');

    // EDIT / UPDATE / DELETE harus pakai parameter {product}
    Route::get('/partner/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/partner/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/partner/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


// ===============================
// PARTNER & SUPER ADMIN ONLY
// ===============================
Route::middleware(['auth', 'role:super_admin,partner'])->group(function () {

    Route::get('/lapangan/create', [LapanganController::class, 'create'])->name('lapangan.create');
    Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');

    Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('lapangan.edit');
    Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('lapangan.update');

    Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');
});


require __DIR__.'/auth.php';
