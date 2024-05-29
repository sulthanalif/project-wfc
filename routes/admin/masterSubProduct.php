<?php

use App\Http\Controllers\Admin\SubProductController;
use Illuminate\Support\Facades\Route;

Route::resource('sub-product', SubProductController::class);
