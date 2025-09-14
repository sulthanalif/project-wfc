<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\CashFlowController;
use App\Http\Controllers\Admin\SpendingController;
use App\Http\Controllers\Admin\SpendingTypeController;

Route::resource('spending', SpendingController::class);
Route::resource('income', IncomeController::class);
Route::resource('loan', LoanController::class)->except(['create', 'edit']);
Route::get('/cash-flow', [CashFlowController::class, 'index'])->name('cash-flow.index');
Route::resource('/procurement', ProcurementController::class);


Route::post('/loan/{loan}/payment', [LoanController::class, 'storePayment'])->name('loan.storePayment');
Route::delete('/loan/{loan}/payment/{payment}', [LoanController::class, 'deletePayment'])->name('loan.deletePayment');

Route::get('/export-spending', [SpendingController::class, 'export'])->name('spending.export');
Route::get('/export-income', [IncomeController::class, 'export'])->name('income.export');
Route::get('/export-loan', [LoanController::class, 'export'])->name('loan.export');

Route::get('/spending-type', [SpendingTypeController::class, 'index'])->name('type-spending.index');
Route::post('/spending-type', [SpendingTypeController::class, 'storeOrUpdate'])->name('type-spending.storeOrUpdate');
Route::delete('/spending-type/{spendingType}', [SpendingTypeController::class, 'destroy'])->name('type-spending.destroy');
