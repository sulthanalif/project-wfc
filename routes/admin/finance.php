<?php

use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\SpendingController;
use App\Http\Controllers\Admin\SpendingTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('spending', SpendingController::class);
Route::resource('income', IncomeController::class);

Route::get('/export-spending', [SpendingController::class, 'export'])->name('spending.export');
Route::get('/export-income', [IncomeController::class, 'export'])->name('income.export');

Route::get('/spending-type', [SpendingTypeController::class, 'index'])->name('type-spending.index');
Route::post('/spending-type', [SpendingTypeController::class, 'storeOrUpdate'])->name('type-spending.storeOrUpdate');
Route::delete('/spending-type/{spendingType}', [SpendingTypeController::class, 'destroy'])->name('type-spending.destroy');
