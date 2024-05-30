<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReportController;

Route::get('/total-deposit', [ReportController::class, 'totalDeposit'])->name('totalDeposit');
Route::get('/product-detail', [ReportController::class, 'productDetail'])->name('rproductDetail');
Route::get('/instalment', [ReportController::class, 'instalment'])->name('instalment');
Route::get('/requirement', [ReportController::class, 'requirement'])->name('requirement');
