<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes([
    // 'login' => true,
    // 'logout' => false,
    // 'register' => false,
    // 'reset' => false,
    'verify' => true
]);

Route::get('/', function () {
    return redirect()->route('home');
});

Route::view('/user/register', 'user.register.index');
Route::view('/admin/template', 'welcome');
Route::view('/user/datatable', 'user.log.index');
Route::view('/admin/datatable', 'admin.data.index');
Route::view('/admin/dashboard', 'admin.dashboard.index');
Route::view('/user/form/perusahaan', 'user.register.form.perusahaan');
Route::view('/user/form/perorangan', 'user.register.form.perorangan');
Route::view('/register/ulang', 'auth.register_ulang')->name('register_ulang');
// Route::view('/login', 'auth.index');

Route::middleware(['verified'])->group(function () {
    Route::view('/home', 'welcome')->name('home');
});


