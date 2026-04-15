<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| ROUTE AUTHENTIKASI
|--------------------------------------------------------------------------
*/
// Halaman login
Route::get('/', [AuthController::class, 'loginForm'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

// Logout (harus login)
Route::middleware('login')->get('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE YANG WAJIB LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('login')->group(function () {

    // ===============================
    // DASHBOARD
    // ===============================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/grafik/{tahun}', [DashboardController::class, 'grafikByTahun'])
        ->name('dashboard.grafik');

    // ===============================
    // CRUD PENGHUNI
    // ===============================
    Route::prefix('penghuni')->group(function () {
        Route::get('/', [PenghuniController::class, 'index'])->name('penghuni.index');
        Route::get('/create', [PenghuniController::class, 'create'])->name('penghuni.create');
        Route::post('/store', [PenghuniController::class, 'store'])->name('penghuni.store');
        Route::get('/edit/{id}', [PenghuniController::class, 'edit'])->name('penghuni.edit');
        Route::put('/update/{id}', [PenghuniController::class, 'update'])->name('penghuni.update');
        Route::delete('/delete/{id}', [PenghuniController::class, 'destroy'])->name('penghuni.destroy');
    });

    // ===============================
    //  RESOURCE PEMASUKAN
    // ===============================
    Route::resource('pemasukan', PemasukanController::class);

    // ===============================
    // SEARCH + RESOURCE PENGELUARAN
    // ===============================
    Route::get('/pengeluaran/search', [PengeluaranController::class, 'search'])->name('pengeluaran.search');
    Route::resource('pengeluaran', PengeluaranController::class);

    // ===============================
    // LAPORAN
    // ===============================
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/excel/{bulan}/{tahun}', [LaporanController::class, 'exportExcel']);

});
