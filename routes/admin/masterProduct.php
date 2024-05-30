<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductController;
use Illuminate\Support\Facades\Route;

Route::resource('product', ProductController::class);

// tambah sub product
Route::post('/product/{product}/sub-product', [ProductController::class, 'addSubProduct'])->name('product.addSubProduct');

//hapus sub product
Route::delete('/product/{product}/sub-product/{productSubProduct}', [ProductController::class, 'destroySub'])->name('product.destroySub');

