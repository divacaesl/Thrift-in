<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PencairanController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource routes for Admin/Kasir
    Route::resource('kategori', KategoriController::class);
    Route::resource('penitip', PenitipController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('pencairan', PencairanController::class);
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
