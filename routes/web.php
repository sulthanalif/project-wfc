<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\AgentProfileController;
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



Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth', 'active'], function () {


    //super_admin, finance_admin, admin
    Route::group(['middleware' => 'role:super_admin|admin|finance_admin'], function () {
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('dashboard-admin');
            Route::group(['middleware' => 'role:super_admin'], function () {
                require __DIR__ . '/admin/masterUser.php';
            });

        require __DIR__ . '/admin/masterCatalog.php';
    });

    //agent
    Route::group(['middleware' => ['role:agent', 'verified']], function () {
        Route::get('/agent', [DashboardController::class, 'index'])->name('dashboard-agent');
        require __DIR__ . '/agent/profile.php';
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


