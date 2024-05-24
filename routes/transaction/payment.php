<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transaction\PaymentController;



Route::post('/payment/image/{order}', [PaymentController::class, 'storePaymentImage'])->name('storePaymentImage');
Route::post('/payment/{order}', [PaymentController::class, 'storePayment'])->name('storePayment');
Route::delete('/payment/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');

Route::get('/payment/check/{order}', [PaymentController::class, 'check'])->name('payment.check');
