<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\LokasiController;

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/wilayah/searchkabupaten', [PegawaiController::class, 'searchKabupaten']);
    Route::get('/wilayah/searchwilayah', [PegawaiController::class, 'searchLocal']);
    Route::get('/pegawaisearch', [PegawaiController::class, 'search']);
    Route::get('/checkusername', [PegawaiController::class, 'checkusername']);
    Route::get('/lokasikantor', [LokasiController::class, 'getLokasi'])->name('api.lokasikantor');
    Route::get('/lokasipegawai', [LokasiController::class, 'getPegawai'])->name('api.lokasipegawai');
    Route::get('/setting/data', [LokasiController::class,'getTunjangan'])->name('api.setting.data');

    });


