<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ProyekController;

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

// Routing untuk CRUD Mahasiswa
Route::resource('mahasiswa', MahasiswaController::class);

// Routing untuk CRUD Dosen
Route::resource('dosen', DosenController::class);

// Routing untuk CRUD Proyek
Route::resource('proyek', ProyekController::class);