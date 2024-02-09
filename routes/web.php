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

Route::get('/', function () {
    return view('welcome');
});

Route::view('/user/register', 'user.register.index');
Route::view('/admin/template', 'welcome');
Route::view('/user/datatable', 'user.log.index');
Route::view('/admin/datatable', 'admin.data.index');
Route::view('/login', 'auth.index');
