<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\SubAgentController;
// use App\Models\Administration;
use App\Models\SubAgent;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/', [LandingpageController::class, 'index'])->name('landing-page');
Route::get('/company-profile', [LandingpageController::class, 'profile'])->name('company-profile');
Route::get('/catalogs-product', [LandingpageController::class, 'catalogs'])->name('catalogs-product');


// Route::get('/login', [LoginController::class, 'index'])



Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth',], function () {
    Route::get('/agent', [DashboardController::class, 'noActive'])->name('nonactive');

    //kelola sub agent
    Route::resource('sub-agent', SubAgentController::class);

    //super_admin, finance_admin, admin
    Route::group(['middleware' => 'role:super_admin|admin|finance_admin', 'active'], function () {
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('dashboard-admin');

    });
        //admin, super_admin
        Route::group(['middleware' => 'role:admin|super_admin'], function () {
            require __DIR__ . '/admin/masterUser.php';
            require __DIR__ . '/admin/masterCatalog.php';
            require __DIR__ . '/admin/masterPackage.php';
            require __DIR__ . '/admin/masterProduct.php';
            require __DIR__ . '/admin/masterSupplier.php';

            Route::get('/administration/{user}', [AdministrationController::class, 'getAdministration'])->name('getAdministration');
        });

        //finance_admin, super_admin
        Route::group(['middleware' => 'role:finance_admin|super_admin'], function () {

        });


    //agent
    Route::group(['middleware' => ['role:agent', 'verified']], function () {
        Route::group(['middleware' => ['active']], function () {
            Route::get('/agent', [DashboardController::class, 'index'])->name('dashboard-agent');
            require __DIR__ . '/agent/profile.php';
            require __DIR__ . '/agent/changeEmailPass.php';
        });

        Route::get('/new-agent',[AdministrationController::class, 'index'])->name('nonactive');
        Route::post('/new-agent',[AdministrationController::class, 'store'])->name('upload');
        Route::get('/waiting',[AdministrationController::class, 'waiting'])->name('waiting');
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


