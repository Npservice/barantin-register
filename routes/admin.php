<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BaratinController;
use App\Http\Controllers\Admin\PemohonController;
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

        Route::prefix('baratin')->name('baratin.')->group(function () {
            Route::get('/datatable/dokumen/{id}', [BaratinController::class, 'datatablePendukung'])->name('datatable.pendukung');
            Route::post('/confirm/register/{id}', [BaratinController::class, 'confirmRegister'])->name('confirm.register');
        });

        // Route::resource('master-upt', MasterUptController::class)->except('show');
        Route::resource('baratin', BaratinController::class)->only(['index', 'show']);
        Route::resource('admin-user', AdminUserController::class)->except('show');
        Route::resource('pemohon', PemohonController::class)->only(['index', 'destroy']);

    });


});
