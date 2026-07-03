<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PackageController;

Route::get('/package/archive', [PackageController::class, 'archive'])->name('package.archive');
Route::get('/package/archive/{package}', [PackageController::class, 'archiveShow'])->name('package.archiveShow');
Route::resource('package', PackageController::class);