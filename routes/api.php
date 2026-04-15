<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;

Route::post('/login', [AuthController::class, 'apiLogin']);

Route::get('/penghuni', [PenghuniController::class, 'apiIndex']);
Route::post('/penghuni', [PenghuniController::class, 'apiStore']);
Route::put('/penghuni/{id}', [PenghuniController::class, 'apiUpdate']);
Route::delete('/penghuni/{id}', [PenghuniController::class, 'apiDelete']);
Route::get('/penghuni/search', [PenghuniController::class, 'apiSearch']);

Route::get('/kamar', [PenghuniController::class, 'apiKamar']);

Route::get('/pemasukan', [PemasukanController::class, 'apiIndex']);
Route::post('/pemasukan', [PemasukanController::class, 'apiStore']);

Route::get('/pengeluaran', [PengeluaranController::class, 'apiIndex']);
Route::post('/pengeluaran', [PengeluaranController::class, 'apiStore']);
Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy']);

Route::get('/laporan', [LaporanController::class, 'apiIndex']);