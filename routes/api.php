<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SendEmaliInvokableController;


Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('api.register');
Route::post('/login', [AuthenticatedController::class, 'store'])
    ->name('api.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticatedController::class, 'destroy'])
        ->name('api.logout');

    // User routes
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

    // Email routes
    Route::get('/users/{user}/emails', [EmailController::class, 'index'])
    ->name('api.emails.index');
    Route::post('/users/{user}/emails', [EmailController::class, 'store'])
    ->name('api.emails.store');
    Route::get('/users/{user}/emails/{email}', [EmailController::class, 'show'])
    ->name('api.emails.show');
    Route::put('/users/{user}/emails/{email}', [EmailController::class, 'update'])
    ->name('api.emails.update');
    Route::patch('/users/{user}/emails/{email}', [EmailController::class, 'update'])
    ->name('api.emails.patch');
    Route::delete('/users/{user}/emails/{email}', [EmailController::class, 'destroy'])
    ->name('api.emails.destroy');
    Route::post('/users/send-welcome', SendEmaliInvokableController::class)
    ->name('api.users.send-welcome');
});
