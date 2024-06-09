<?php

use App\Http\Controllers\Transaction\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::delete('/order/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
