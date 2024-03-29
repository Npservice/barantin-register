<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterUptController;
use App\Http\Controllers\Admin\Auth\LoginController;


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('admin', function () {
        return redirect()->route('admin.auth.index');
    });

    Route::prefix('login')->name('auth.')->group(function () {
        Route::get('', [LoginController::class, 'index'])->name('index');
        Route::post('auth', [LoginController::class, 'login'])->name('login');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('', [DashboardController::class, 'index'])->name('index');
        });

        Route::prefix('permohonan')->name('permohonan.')->group(function () {
            Route::get('/datatable/dokumen/{id}', [PermohonanController::class, 'datatablePendukung'])->name('datatable.pendukung');
            Route::post('/confirm/register/{id}', [PermohonanController::class, 'confirmRegister'])->name('confirm.register');
        });
        Route::prefix('pendaftar')->name('pendaftar.')->group(function () {

            Route::prefix('datatable')->name('datatable.')->group(function () {
                Route::get('/dokumen/{id}', [PendaftarController::class, 'datatablePendukung'])->name('pendukung');
                Route::get('data/cabang', [PendaftarController::class, 'datatableCabang'])->name('cabang');
                Route::get('data/{pemohon}', [PendaftarController::class, 'datatablePeoranganPerusahaanInduk'])->name('pemohon');

                Route::view('perorangan', 'admin.pendaftar.table.perorangan')->name('perorangan');
                Route::view('induk', 'admin.pendaftar.table.induk')->name('induk');
                Route::view('cabang', 'admin.pendaftar.table.cabang')->name('cabang');
            });
            Route::post('/block/akses/{id}', [PendaftarController::class, 'BlockAccessPendaftar'])->name('block.akses');
            Route::post('/open/akses/{id}', [PendaftarController::class, 'OpenkAccessPendaftar'])->name('open.akses');
            Route::post('/create/user/{id}', [PendaftarController::class, 'CreateUser'])->name('create.user');
            Route::post('/send/username/{id}', [PendaftarController::class, 'SendUsernamePasswordEmail'])->name('send.user');
        });

        // Route::resource('master-upt', MasterUptController::class)->except('show');
        Route::resource('pendaftar', PendaftarController::class)->only(['index', 'show']);
        Route::resource('admin-user', AdminUserController::class)->except('show');
        Route::resource('permohonan', PermohonanController::class)->only(['index', 'destroy', 'show']);

    });


});
