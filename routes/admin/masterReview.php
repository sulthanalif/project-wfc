<?php

use App\Http\Controllers\Admin\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
Route::get('/review/{review}', [ReviewController::class, 'show'])->name('review.show');
Route::get('/review/create', [ReviewController::class, 'create'])->name('review.create');
Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
Route::get('/review/edit/{review}', [ReviewController::class, 'edit'])->name('review.edit');
Route::put('/review/{review}', [ReviewController::class, 'update'])->name('review.update');
Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');