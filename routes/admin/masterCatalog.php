<?php

use App\Http\Controllers\Admin\CatalogController;
use Illuminate\Support\Facades\Route;

Route::resource('catalog', CatalogController::class);
