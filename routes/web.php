<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Admin\DashboardAdminController;
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


// Route::get('/login', [LoginController::class, 'index'])



Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    //user profile
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/profile', [UserProfileController::class, 'show'])->name('users.profile');
    Route::get('/{user}/profile/edit', [UserProfileController::class, 'edit'])->name('users.profile.edit');
    Route::put('/{user}/profile', [UserProfileController::class, 'update'])->name('users.profile.update');

    //super_admin, finance_admin, admin
    Route::group(['middleware' => 'role:super_admin|admin|finance_admin'], function () {
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('dashboard-admin');
    });

    //agent
    Route::group(['middleware' => 'role:agent'], function () {
        Route::get('/agent', [DashboardController::class, 'index'])->name('dashboard-agent');
    });
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/dashboard-admin', function (){
//     return '<h1>Dashboard Admin</h1>';
// })->name('dashboard-admin');
// Route::get('/dashboard-user', function (){
//     return '<h1>Dashboard User</h1>';
// })->name('dashboard-user');

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard-admin');
// });
// Route::get('/dashboard-admin', function (){
//     return view('cms.admin.index');
// })->name('dashboard-admin');
// Route::get('/dashboard-user', function (){
//     return view('cms.agen.index');
// })->name('dashboard-user');

// Route::get('temp', function (){
//     return view('cms.admin.users.index');
// })->name('users');
