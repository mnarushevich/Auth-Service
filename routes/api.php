<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    Route::apiResource('users', UserController::class);

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('me', [AuthController::class, 'me'])->name('me');
        Route::post('verify', [AuthController::class, 'verify'])->name('verify');
    });
});
