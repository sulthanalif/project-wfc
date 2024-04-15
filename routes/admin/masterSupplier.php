<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SupplierController;

Route::resource('supplier', SupplierController::class);
