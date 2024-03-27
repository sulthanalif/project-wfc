<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Auth\AuthController;
// use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (){
    return view('welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/dashboard-admin', function (){
//     return '<h1>Dashboard Admin</h1>';
// })->name('dashboard-admin');
// Route::get('/dashboard-user', function (){
//     return '<h1>Dashboard User</h1>';
// })->name('dashboard-user');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard-admin');
});
Route::get('/dashboard-admin', function (){
    return view('cms.admin.index');
})->name('dashboard-admin');
Route::get('/dashboard-user', function (){
    return view('cms.agen.index');
})->name('dashboard-user');

Route::get('temp', function (){
    return view('cms.admin.users.index');
})->name('users');
