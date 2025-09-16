<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RewardController;

Route::resource('rewards', RewardController::class);