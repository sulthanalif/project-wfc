<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;

Route::post('/import-package', [PackageController::class, 'import'])->name('import.package');
Route::post('/import-product', [ProductController::class, 'import'])->name('import.product');
