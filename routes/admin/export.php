<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;



Route::get('/export-package', [PackageController::class, 'export'])->name('export.package');
Route::get('/export-product', [ProductController::class, 'export'])->name('export.product');
