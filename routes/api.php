<?php

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarantinController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\BarantinMitraController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->name('api.')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');

    Route::get('failed', function () {
        return ApiResponse::errorResponse('Unauthorized', 401);
    })->name('failed');

    Route::prefix('mitra')->name('mitra.')->group(function () {
        Route::get('all/{take}', [BarantinMitraController::class, 'GetAllDataMitra'])->name('all');
        Route::get('{mitra_id}', [BarantinMitraController::class, 'GetAllDataMitraByID'])->name('find.one');


        Route::prefix('pj')->name('pj.')->group(function () {
            Route::get('induk/{barantin_id}', [BarantinMitraController::class, 'GetAllDataMitraByBaratinID'])->name('induk.get');
            Route::get('cabang/{barantin_cabang_id}', [BarantinMitraController::class, 'GetAllDataMitraByBaratinCabangID'])->name('cabang.get');
        });
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix('barantin')->name('barantin.')->group(function () {
            Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
                Route::get('induk/{take}', [BarantinController::class, 'getAllDataBarantinPerusahaanInduk'])->name('induk.get');
                Route::get('cabang/{take}', [BarantinController::class, 'getAllDataBarantinPerusahaanCabang'])->name('cabang.get');
                Route::get('induk/{barantin_id}/detil', [BarantinController::class, 'detilDataBarantinPerusahaanIndukBarantinID'])->name('induk.detil');
                Route::get('cabang/{barantin_id}/detil', [BarantinController::class, 'detilDataBarantinPerusahaanCabangByBarantinID'])->name('cabang.detil');
            });
            Route::get('perorangan/{take}', [BarantinController::class, 'getAllDataBarantinPerorangan'])->name('perorangan.get');
            Route::get('perorangan/{barantin_id}/detil', [BarantinController::class, 'detilDataBarantinPeroranganByBarantinID'])->name('perorangan.detil');
            Route::get('{register_id}/register', [BarantinController::class, 'getDataBarantinByRegisterID'])->name('barantin.detail.get');
        });
    });
});

