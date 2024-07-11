<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MasterLandingPageController;

Route::get('/landing-page', [MasterLandingPageController::class, 'index'])->name('landing-page.index');
Route::put('/landing-page/header/{header}', [MasterLandingPageController::class, 'updateHeader'])->name('landing-page.updateHeader');
Route::put('/landing-page/profile/{profile}', [MasterLandingPageController::class, 'updateProfile'])->name('landing-page.updateProfile');
Route::put('/landing-page/contact/{contact}', [MasterLandingPageController::class, 'updateContact'])->name('landing-page.updateContact');
