<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PeriodController;

Route::get('/options', [OptionController::class, 'index'])->name('options.index');

//period
Route::post('/options/period', [PeriodController::class, 'addOrUpdatePeriod'])->name('addOrUpdatePeriod');
Route::delete('/options/period/{period}', [PeriodController::class, 'deletePeriod'])->name('deletePeriod');
Route::post('/options/period/activate', [PeriodController::class, 'activatePeriod'])->name('activatePeriod');
