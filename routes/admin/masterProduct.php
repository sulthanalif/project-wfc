<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductController;
use Illuminate\Support\Facades\Route;

Route::get('/product/archive', [ProductController::class, 'archive'])->name('product.archive');
Route::get('/product/archive/{product}', [ProductController::class, 'archiveShow'])->name('product.archive.show');

Route::resource('product', ProductController::class);

// tambah sub product
Route::post('/product/{product}/sub-product', [ProductController::class, 'addSubProduct'])->name('product.addSubProduct');

//hapus sub product
Route::delete('/product/{product}/sub-product/{productSubProduct}', [ProductController::class, 'destroySub'])->name('product.destroySub');
