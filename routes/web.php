<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Models\Lapangan;


// ===============================
// HALAMAN UTAMA
// ===============================
Route::get('/', function () {
    $lapangans = Lapangan::all();
    return view('welcome', compact('lapangans'));
})->name('home');


// ===============================
// AUTH USER ROUTES (Customer, Partner, Super Admin)
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

    // GABUNG MITRA
    Route::get('/daftar-mitra', [ProfileController::class, 'partnerForm'])
        ->name('partner.form');

    Route::post('/daftar-mitra', [ProfileController::class, 'registerPartner'])
        ->name('partner.register');
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
