<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AbsensiController;

Route::post('/login', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->post('/absensi/scan', [AbsensiController::class, 'scan']);
Route::middleware('auth:sanctum')->get('/absensi/today', [AbsensiController::class, 'today']);

Route::post('/absensi/scanuid', [AbsensiController::class, 'scanuid']);


// Route::middleware('web')->post('/postlogin', [AuthController::class, 'postlogin']);
// Route::middleware('web')->get('/captcha', [AuthController::class, 'captcha']);
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/wilayah/searchkabupaten', [PegawaiController::class, 'searchKabupaten']);
    Route::get('/wilayah/searchwilayah', [PegawaiController::class, 'searchLocal']);
    Route::get('/pegawaisearch', [PegawaiController::class, 'search']);
    Route::get('/checkusername', [PegawaiController::class, 'checkusername']);
    Route::get('/lokasikantor', [LokasiController::class, 'getLokasi'])->name('api.lokasikantor');
    Route::get('/lokasipegawai', [LokasiController::class, 'getPegawai'])->name('api.lokasipegawai');
    Route::get('/setting/data', [LokasiController::class,'getTunjangan'])->name('api.setting.data');

    });


