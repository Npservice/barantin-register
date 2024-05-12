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
        return ApiResponse::ErrorResponse('Unauthorized', 401);
    })->name('failed');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('barantin/{take}', [BarantinController::class, 'GetAllDataBarantin'])->name('barantin.get');
        Route::get('barantin/{id}/detail', [BarantinController::class, 'GetDataBarantinByID'])->name('barantin.get');
        Route::prefix('mitra')->name('mitra.')->group(function () {
            Route::get('all/{take}', [BarantinMitraController::class, 'GetAllDataMitra'])->name('all');
            Route::get('{mitra_id}', [BarantinMitraController::class, 'GetAllDataMitraByID'])->name('find.one');
            Route::get('barantin/{barantin_id}', [BarantinMitraController::class, 'GetAllDataMitraByBaratinID'])->name('barantin.get');
            Route::get('cabang/{barantin_cabang_id}', [BarantinMitraController::class, 'GetAllDataMitraByBaratinCabangID'])->name('cabang.get');
        });
    });
});

