<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MahasiswaController;
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');

use App\Http\Controllers\BookController;

// Rute untuk menampilkan buku yang tersedia
Route::get('/books/available', [BookController::class, 'available']);

// Rute untuk menampilkan semua buku
Route::get('/books', [BookController::class, 'index']);

// Rute untuk menampilkan detail buku
Route::get('/books/{id}', [BookController::class, 'show']);



Route::get('/', function () {
    return view('welcome');
});
