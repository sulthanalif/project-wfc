<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductController;
use Illuminate\Support\Facades\Route;

Route::resource('product', ProductController::class);

//subProduct
Route::prefix('product')->group(function () {
    Route::post('/subproduct/{product}', [SubProductController::class, 'store'])->name('subProduct.store');
    Route::delete('/subproduct/delete/{product}/{subProduct}', [SubProductController::class, 'destroy'])->name('subProduct.destroy');
});

