<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\PreRegisterController;


Route::prefix('register')->name('register.')->group(function () {
    Route::get('baru', [PreRegisterController::class, 'index'])->name('index');
    Route::get('ulang', [PreRegisterController::class, 'create'])->name('create');

    Route::post('email', [PreRegisterController::class, 'NewRegister'])->name('new');

    Route::get('verify/{id}/{token}', [PreRegisterController::class, 'TokenVerify'])->name('verify');
    Route::post('regenerate', [PreRegisterController::class, 'Regenerate'])->name('regenerate');

    Route::get('failed', [PreRegisterController::class, 'RegisterFailed'])->name('failed')->middleware('registerfailed:PreRegisterController');

    Route::get('formulir/{id}', [PreRegisterController::class, 'RegisterFormulirIndex'])->name('formulir');
    Route::get('form/{id}', [PreRegisterController::class, 'RegisterForm'])->name('formulir');

    Route::get('status', [PreRegisterController::class, 'StatusRegister'])->name('status');
});
