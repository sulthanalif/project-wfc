<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::resource('user', UserController::class);
Route::put('user/{user}/status', [UserController::class, 'changeStatus'])->name('user.status');


//getAdmin
Route::get('/user-admin', [UserController::class, 'getAdmin'])->name('get.admin');

//change role user
Route::post('/user/role/{user}', [UserController::class, 'changeRole'])->name('user.role');
