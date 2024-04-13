<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::resource('user', UserController::class);
Route::put('user/{user}/status', [UserController::class, 'changeStatus'])->name('user.status');
