<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transaction\PaymentController;



Route::post('/payment/image/{order}', [PaymentController::class, 'storePaymentImage'])->name('storePaymentImage');
Route::post('/payment/{order}', [PaymentController::class, 'storePayment'])->name('storePayment');