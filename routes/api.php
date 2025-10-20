<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/template', [TemplateController::class, 'index']);

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.api');
    Route::get('/profile', [AuthController::class, 'check'])->name('auth.api.check');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.api.logout');
});
