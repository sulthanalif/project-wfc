<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DownloadFileController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductController;

// use App\Models\Package;

Route::post('/import-package', [PackageController::class, 'import'])->name('import.package');
Route::post('/import-product', [ProductController::class, 'import'])->name('import.product');
Route::post('/import-agent', [UserController::class, 'import'])->name('import.agent');
Route::post('/import-sub-product', [SubProductController::class, 'import'])->name('import.sub-product');


//download format
Route::get('/download/{file}', [DownloadFileController::class, 'download'])->name('download.file');
