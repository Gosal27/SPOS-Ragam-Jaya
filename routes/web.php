<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\OptimalisasiController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PeramalanController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsLogin;

use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class,'loginView']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout']);

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');

Route::get('/', [DashboardController::class, 'index']);

Route::middleware(IsLogin::class)->group(function () {

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/create', [ProdukController::class, 'create'])->name('create');
Route::post('/produk/store', [ProdukController::class, 'store'])->name('store');
Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
Route::post('/produk/{id}/update-stok', [ProdukController::class, 'updateStok'])->name('produk.updateStok');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/tambah', [TransaksiController::class, 'addToCart'])->name('transaksi.tambah');
Route::get('/transaksi/hapus/{id}', [TransaksiController::class, 'removeFromCart'])->name('transaksi.hapus');
Route::get('/transaksi/reset', [TransaksiController::class, 'resetCart'])->name('transaksi.reset');
Route::post('/transaksi/checkout', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
Route::get('/transaksi/nota/{id}', [TransaksiController::class, 'nota'])->name('transaksi.nota');


Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/export', [DashboardController::class, 'exportCsv'])->name('dashboard.export');

// HALAMAN UTAMA (GET)
Route::get('/peramalan', [PeramalanController::class, 'index'])->name('peramalan.index');

// SIMPAN PERAMALAN (POST)
Route::post('/peramalan', [PeramalanController::class, 'store'])->name('peramalan.store');

Route::get('/optimalisasi', [OptimalisasiController::class, 'index'])->name('optimalisasi.index');

// Halaman backup & restore
Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');

// Tombol proses backup
Route::post('/backup/process', [BackupController::class, 'backup'])->name('backup.process');

// Tombol restore database
Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');

});
