<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transaction\PaymentController;


Route::post('/payment/{order}', [PaymentController::class, 'storePayment'])->name('storePayment');
Route::put('/payment/{payment}', [PaymentController::class, 'updatePayment'])->name('updatePayment');
Route::delete('/payment/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');

Route::get('/payment/check/{order}', [PaymentController::class, 'check'])->name('payment.check');
// Route::get('/payment/check/{order}/{payment}', [PaymentController::class, 'cekView'])->name('payment.checkview');

Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/payment/{user}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/payment/{user}/{order}', [PaymentController::class, 'showPayment'])->name('payment.detail');