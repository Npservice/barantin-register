<?php

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarantinController;
use App\Http\Controllers\Api\User\UptController;
use App\Http\Controllers\Api\User\PpjkController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\User\MitraController;
use App\Http\Controllers\Api\User\CabangController;
use App\Http\Controllers\Api\BarantinPpjkController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\BarantinMitraController;
use App\Http\Controllers\Api\User\UserLoginController;

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

/**
 * admin api routes
 * prefix v1 => api version
 */
Route::prefix('v1')->name('api.')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');

    Route::get('failed', function () {
        return ApiResponse::errorResponse('Unauthorized', 401);
    })->name('failed');


    Route::middleware(['auth:sanctum', 'api.version:v1'])->group(function () {
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
        Route::prefix('mitra')->name('mitra.')->group(function () {

            Route::get('{mitra_id}', [BarantinMitraController::class, 'GetAllDataMitraByID'])->name('find.one');

            Route::prefix('pj')->name('pj.')->group(function () {

                Route::get('induk/{barantin_id}', [BarantinMitraController::class, 'GetAllDataMitraByBaratinID'])->name('induk.get');
                Route::get('cabang/{barantin_cabang_id}', [BarantinMitraController::class, 'GetAllDataMitraByBaratinCabangID'])->name('cabang.get');

                Route::prefix('all')->name('all.')->group(function () {
                    Route::get('induk/{take}', [BarantinMitraController::class, 'GetAllDataMitraInduk'])->name('induk');
                    Route::get('cabang/{take}', [BarantinMitraController::class, 'GetAllDataMitraCabang'])->name('cabang');
                });
            });
        });
        Route::prefix('ppjk')->name('ppjk.')->group(function () {
            Route::get('cek-npwp', [BarantinPpjkController::class, 'cekNpwpPpjk'])->name('cek.npwp');
            Route::get('{take}', [BarantinPpjkController::class, 'getPpjk'])->name('all.admin');
            Route::get('{ppjk_id}/detil', [BarantinPpjkController::class, 'getDetailPpjk'])->name('one.admin');
        });
    });
});
/**
 * api user
 * prefix v2 => api version 2
 */
Route::prefix('v2')->name('api.')->group(function () {
    Route::prefix('user')->group(function () {

        Route::post('login', [UserLoginController::class, 'loginUser'])->name('login');

        Route::middleware(['auth:sanctum', 'api.version:v2'])->group(function () {
            Route::get('upt', [UptController::class, 'getAllUptUser'])->name('upt');
            Route::get('profile', [ProfileController::class, 'getProfileUser'])->name('profile');

            Route::prefix('mitra')->name('mitra.')->group(function () {
                Route::get('', [MitraController::class, 'getAllMitraUser'])->name('all');
                Route::get('{mitra_id}', [MitraController::class, 'getMitraByID'])->name('one');
            });

            Route::prefix('cabang')->name('cabang.')->group(function () {
                Route::get('', [CabangController::class, 'getCabangPerusahaanInduk'])->name('all.user');
                Route::get('{barantin_cabang_id}', [CabangController::class, 'getDetailCabangPerusahaanInduk'])->name('one.user');
            });

            Route::prefix('ppjk')->name('ppjk.')->group(function () {
                Route::get('', [PpjkController::class, 'getPpjk'])->name('all.user');
                Route::get('{ppjk_id}', [PpjkController::class, 'getDetailPpjk'])->name('one.user');
            });
        });
    });
});
