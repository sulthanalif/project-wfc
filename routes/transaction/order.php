<?php

use App\Http\Controllers\Transaction\DetailOrderController;
use App\Http\Controllers\Transaction\OrderAmanController;
use App\Http\Controllers\Transaction\OrderController;
use App\Http\Controllers\Transaction\OrderFullController;
use Illuminate\Support\Facades\Route;

Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');

Route::get('/order/full', [OrderFullController::class, 'index'])->name('order.full.index');
Route::get('/order/full/create', [OrderFullController::class, 'create'])->name('order.full.create');
Route::post('/order/full', [OrderFullController::class, 'store'])->name('order.full.store');
Route::get('/order/full/{order}', [OrderFullController::class, 'show'])->name('order.full.show');

Route::get('/order/titik-aman', [OrderAmanController::class, 'index'])->name('order.aman.index');
Route::get('/order/titik-aman/create', [OrderAmanController::class, 'create'])->name('order.aman.create');
Route::post('/order/titik-aman', [OrderAmanController::class, 'store'])->name('order.aman.store');
Route::get('/order/titik-aman/{order}', [OrderAmanController::class, 'show'])->name('order.aman.show');

Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::delete('/order/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
