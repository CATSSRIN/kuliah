<?php

// File: routes/web.php
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;

Route::get('/', function () {
    return view('welcome');
});

// Mendaftarkan semua route CRUD untuk buku
Route::resource('books', BookController::class);
Route::resource('authors', AuthorController::class);