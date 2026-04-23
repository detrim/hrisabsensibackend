<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\SettingTunjanganTransportController;
use App\Http\Controllers\TunjanganTransportPegawaiController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PegawaiOnlineController;


Route::middleware(['auth'])->group(function () {
    Route::get('/user', [KelolaUserController::class, 'index'])->name('user.index');
    Route::get('/user/{id}/edit', [KelolaUserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}/update', [KelolaUserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}/detail', [KelolaUserController::class, 'detail'])->name('user.detail');

        Route::prefix('superadmin')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::get('/log/activity', [ActivityController::class, 'index'])->name('log.activity');
            Route::get('/online/index', [PegawaiOnlineController::class, 'index'])->name('online.index');
            Route::get('/online', [PegawaiOnlineController::class, 'data'])->name('online');
            Route::get('/user/create', [KelolaUserController::class, 'create'])->name('user.create');
            Route::post('/user/store', [KelolaUserController::class, 'store'])->name('user.store');
            Route::delete('/user/{id}/delete', [KelolaUserController::class, 'delete'])->name('user.destroy');
            });
        Route::prefix('adminhrd')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::get('/pegawai', [DataPegawaiController::class, 'index'])->name('pegawai.index');
            Route::get('/pegawai/search', [DataPegawaiController::class, 'cari'])->name('pegawai.cari');
            Route::get('/pegawai/create', [DataPegawaiController::class, 'create'])->name('pegawai.create');
            Route::post('/pegawai/store', [DataPegawaiController::class, 'store'])->name('pegawai.store');
            Route::get('/pegawai/{id}/detail', [DataPegawaiController::class, 'detail'])->name('pegawai.detail');
            Route::get('/pegawai/{id}/edit', [DataPegawaiController::class, 'edit'])->name('pegawai.edit');
            Route::put('/pegawai/{id}/update', [DataPegawaiController::class, 'update'])->name('pegawai.update');
            Route::delete('/pegawai/delete', [DataPegawaiController::class, 'delete'])->name('pegawai.destroy');
            Route::get('/pegawai/{id}/pdf', [DataPegawaiController::class, 'downloadPdf'])->name('pegawai.pdf');
            Route::get('/pegawai/exportpdf', [DataPegawaiController::class, 'exportPdf'])->name('pegawai.export.pdf');
            Route::get('/pegawai/exportexcel', [DataPegawaiController::class, 'exportExcel'])->name('pegawai.export.excel');
            Route::get('/pegawai/filter', [DataPegawaiController::class, 'filter'])->name('pegawai.filter');
            Route::post('/pegawai/bulkstatus', [DataPegawaiController::class, 'bulkStatus']);

            Route::get('/absensi/{thn}/{bln}/{tgl}/pegawai', [AbsensiController::class, 'absensi'])->name('absensi.hari');
            Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
            Route::post('/absensi/search', [AbsensiController::class, 'search'])->name('absensi.search');
            Route::patch('/absensi/update', [AbsensiController::class, 'update'])->name('absensi.update.ajax');
            Route::patch('/absensi/keterangan', [AbsensiController::class, 'keterangan'])->name('absensi.keterangan.ajax');

            Route::get('/periode/index', [PeriodeController::class, 'index'])->name('periode.index');
            Route::get('/periode/{id}/bulan', [PeriodeController::class, 'bulan'])->name('periode.bulan');
            Route::patch('/periode/hari', [PeriodeController::class, 'hari'])->name('periode.hari');
            Route::delete('/periode/hari', [PeriodeController::class, 'hapushari'])->name('periode.hari.hapus');
            Route::post('/periode/store', [PeriodeController::class, 'store'])->name('periode.store');
            Route::get('/periode/search', [PeriodeController::class, 'search'])->name('periode.search');
            Route::post('/periode/update/{id}', [PeriodeController::class, 'update'])->name('periode.update');
            Route::post('/periode/status/{id}', [PeriodeController::class, 'updateStatus'])->name('periode.update.status');

            Route::get('/lokasi/index', [LokasiController::class, 'index'])->name('lokasi.index');
            Route::post('/lokasi/pegawai', [LokasiController::class, 'pegawai'])->name('lokasipegawai.store');
            Route::post('/lokasi/kantor', [LokasiController::class,'kantor'])->name('lokasikantor.store');
            Route::get('/lokasi/search', [LokasiController::class,'search'])->name('lokasi.search');

            Route::get('/setting/index', [SettingTunjanganTransportController::class,'index'])->name('setting.index');
            Route::post('/setting/store', [SettingTunjanganTransportController::class,'store'])->name('setting.store');

            Route::get('/tunjangan/index', [TunjanganTransportPegawaiController::class,'index'])->name('tunjangan.index');
            Route::get('/tunjangan/search', [TunjanganTransportPegawaiController::class,'search'])->name('tunjangan.search');
            Route::get('/tunjangan/{thn}/{bln}/pegawai/{id}', [TunjanganTransportPegawaiController::class,'tunjangan'])->name('tunjangan.bulan');
            Route::post('/tunjangan/nama', [TunjanganTransportPegawaiController::class,'tunjangansearch'])->name('tunjangan.nama');
    });
        Route::prefix('managerhrd')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('managerhrd');
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/',[AuthController::class,'login']);
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/postlog',[AuthController::class,'postlogin']);
