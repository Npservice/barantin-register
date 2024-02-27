<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\RegisterController;
use App\Http\Controllers\Register\PreRegisterController;


Route::prefix('register')->name('register.')->group(function () {
    Route::get('baru', [PreRegisterController::class, 'index'])->name('index');
    Route::get('ulang', [PreRegisterController::class, 'create'])->name('create');

    Route::post('baru', [PreRegisterController::class, 'NewRegister'])->name('new');
    Route::post('ulang', [PreRegisterController::class, 'RegisterUlang'])->name('ulang');

    Route::get('verify/{id}/{token}', [PreRegisterController::class, 'TokenVerify'])->name('verify');
    Route::post('regenerate', [PreRegisterController::class, 'Regenerate'])->name('regenerate');

    /* RegisterController Handler */
    Route::get('failed', [RegisterController::class, 'RegisterFailed'])->name('failed');

    Route::get('formulir/{id}', [RegisterController::class, 'RegisterFormulirIndex'])->name('formulir.index');
    Route::get('form/{id}', [RegisterController::class, 'RegisterForm'])->name('formulir');


    /* dokumen pendukung route handler */
    Route::prefix('pendukung')->name('pendukung.')->group(function () {
        Route::get('datatable/{id}', [RegisterController::class, 'DokumenPendukungDataTable'])->name('datatable');
        Route::post('store/{id}', [RegisterController::class, 'DokumenPendukungStore'])->name('store');
        Route::delete('destroy/{id}', [RegisterController::class, 'DokumenPendukungDestroy'])->name('destroy');
    });

    Route::get('status', [RegisterController::class, 'StatusRegister'])->name('status');

    Route::get('regenerate', function () {
        return redirect()->route('register.index');
    });
    Route::get('email', function () {
        return redirect()->route('register.index');
    });
    Route::get('', function () {
        return redirect()->route('register.index');
    });
});
