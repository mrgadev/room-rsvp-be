<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route::get('/user', function (Request $request) {

// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/category', [CategoryController:: class, 'index'])->name('category');
    Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::resource('/rooms', RoomController::class)->names('rooms');

    Route::post('/transaction/create', [TransactionController::class, 'store'])->name('transaction.create');
});

