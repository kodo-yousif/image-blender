<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->middleware('throttle:login')->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.admin')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.store');
    Route::post('/admin/users/{user}/expire', [UserController::class, 'expire'])
    ->name('admin.users.expire');
});

Route::middleware(['auth.static'])->group(function () {
    Route::get('/', [ImageController::class, 'index'])->name('home');
    Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
    Route::get('/status/{im1}/{im2}', [ImageController::class, 'statusPage'])->name('status.page');
    Route::get('/check-status/{im1}/{im2}', [ImageController::class, 'checkStatus'])->name('check.status');
    Route::get('/result/{im1}/{im2}', [ImageController::class, 'download'])->name('result');
});
