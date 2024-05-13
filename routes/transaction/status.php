<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transaction\OrderController;
use App\Http\Controllers\Transaction\PaymentController;


Route::post('/acc/{order}', [OrderController::class, 'accOrder'])->name('order.accOrder');
Route::post('/changeOrderStatus/{order}', [OrderController::class, 'changeOrderStatus'])->name('order.changeOrderStatus');
Route::post('/accPayment/{payment}/{order}', [PaymentController::class, 'accPayment'])->name('accPayment');
Route::post('/rejectPayment/{payment}/{order}', [PaymentController::class, 'rejectPayment'])->name('rejectPayment');
Route::post('/changePaymentStatus/{payment}', [PaymentController::class, 'changePaymentStatus'])->name('changePaymentStatus');

//order stats
Route::get('/orderStats', [OrderController::class, 'getOrderStats'])->name('getOrderStats');
