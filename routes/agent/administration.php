<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministrationController;


Route::get('/new-agent',[AdministrationController::class, 'index'])->name('nonactive');
Route::post('/new-agent',[AdministrationController::class, 'store'])->name('upload');
Route::get('/waiting',[AdministrationController::class, 'waiting'])->name('waiting');