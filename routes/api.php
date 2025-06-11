<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('api.register');

Route::post('/login', [AuthenticatedController::class, 'store'])
    ->name('api.login');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
})->name('api.user');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticatedController::class, 'destroy'])
        ->name('api.logout');
    Route::get('/users', [UserController::class, 'index'])
        ->name('api.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('api.users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('api.users.update');
    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('api.users.patch');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('api.users.destroy');
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])
        ->name('api.users.password.update');
    Route::patch('/users/{user}/password', [UserController::class, 'updatePassword'])
        ->name('api.users.password.patch');
});

