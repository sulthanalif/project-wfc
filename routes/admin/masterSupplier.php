<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::resource('supplier', SupplierController::class);
