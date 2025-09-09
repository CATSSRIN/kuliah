<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*closure*/
Route::get('/hello', function () {
    return 'Hello World';
});

Route::get('/about', function () {
    return 'Nama: Caezarlov Nugraha, NPM: 23081010182'; 
});


/*parameter*/
Route::get('/halo/{nama}', function ($nama) {
    return 'Halo, ' . $nama;
});

Route::get('/perkalian/{a}/{b}', function ($a, $b) {
    return $a * $b;
});


/*parameter opsional*/
Route::get('/salam/{nama?}', function ($nama = 'Guest') {
    return 'Halo, ' . $nama;
});


/*Routing Group dengan Prefix*/
Route::prefix('admin')->group(function () {
    Route::get('/home', function () {
        return 'Halaman Admin Home';
    });

    Route::get('/user', function () {
        return 'Daftar User Admin';
    });
});