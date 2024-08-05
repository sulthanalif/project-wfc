<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PackageController;

Route::resource('package', PackageController::class);

Route::get('/package/archive', [PackageController::class, 'archive'])->name('package.archive');
