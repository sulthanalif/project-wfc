<?php

use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\SpendingController;
use Illuminate\Support\Facades\Route;

Route::resource('spending', SpendingController::class);
Route::resource('income', IncomeController::class);
