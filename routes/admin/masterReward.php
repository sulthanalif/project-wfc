<?php

use App\Http\Controllers\Admin\CommissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RewardController;

Route::get('/commissions/archive', [CommissionController::class, 'archive'])->name('commissions.archive');
Route::get('/commissions/archive/{commission}', [CommissionController::class, 'archiveShow'])->name('commissions.archive.show');

Route::resource('rewards', RewardController::class);

Route::resource('commissions', CommissionController::class);