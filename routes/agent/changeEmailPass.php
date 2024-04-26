<?php

use App\Http\Controllers\Auth\UpdateEmailPasswordController;
use Illuminate\Support\Facades\Route;

//email
Route::get('/email', [UpdateEmailPasswordController::class, 'changeEmail'])->name('email.index');
Route::post('/email/checking', [UpdateEmailPasswordController::class, 'checkEmail'])->name('email.checking');
Route::put('/email/{user}', [UpdateEmailPasswordController::class, 'updateEmail'])->name('email.update');

//password
Route::get('/password', [UpdateEmailPasswordController::class, 'changePassword'])->name('password.change');
Route::put('/password/{user}', [UpdateEmailPasswordController::class, 'updatePassword'])->name('password.update');
