<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubAgentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductController;

// master
Route::get('/export-package', [PackageController::class, 'export'])->name('export.package');
Route::get('/export-product', [ProductController::class, 'export'])->name('export.product');
Route::get('/export-agent', [UserController::class, 'export'])->name('export.agent');
Route::get('/export-subagent', [SubAgentController::class, 'export'])->name('export.subAgent');
Route::get('/export-sub-product', [SubProductController::class, 'export'])->name('export.sub-product');

// report
// Route::get('/export-report-totaldeposit', [ReportController::class, 'exportTotalDeposit'])->name('exportTotalDeposit');
