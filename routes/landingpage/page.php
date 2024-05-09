<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingpageController;

Route::get('/', [LandingpageController::class, 'index'])->name('landing-page');
Route::get('/company-profile', [LandingpageController::class, 'profile'])->name('company-profile');
Route::get('/catalogs-product', [LandingpageController::class, 'catalogs'])->name('catalogs-product');