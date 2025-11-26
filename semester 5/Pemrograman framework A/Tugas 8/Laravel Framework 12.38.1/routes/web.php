<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Route Dashboard (Tugas)
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// Route CRUD User
Route::resource('users', UserController::class);