<?php

use App\Models\SubAgent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\GetImageController;
use App\Http\Controllers\SubAgentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Transaction\OrderController;
use App\Http\Controllers\Admin\DistributionController;
// use App\Models\Administration;
use App\Http\Controllers\Transaction\PaymentController;
use App\Http\Controllers\Admin\DashboardAdminController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Admin\ExportDeliveryOrderController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Transaction\ExportInvoiceController;

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

require __DIR__ . '/landingpage/page.php';

//nampilin image
Route::get('storage/images/{path}/{imageName}', [GetImageController::class, 'displayImage'])->name('getImage');

// Route::get('/login', [LoginController::class, 'index'])



Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth',], function () {
    Route::get('/agent', [DashboardController::class, 'noActive'])->name('nonactive');


    //super_admin, finance_admin, admin
    Route::group(['middleware' => 'role:super_admin|admin|finance_admin', 'active'], function () {
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('dashboard-admin');

        //export
        require __DIR__ . '/admin/export.php';

        //import
        require __DIR__ . '/admin/import.php';


        //Report
        Route::group(['prefix' => 'report'], function () {
            require __DIR__ . '/admin/report.php';
            // Route::resource('distribution', DistributionController::class);
        });

        //distribution
        Route::resource('distribution', DistributionController::class);
        Route::group(['prefix' => 'distribution'], function () {
            Route::get('/export/{distribution}', [ExportDeliveryOrderController::class, 'getDeliveryOrder'])->name('export.deliveryOrder');
        });
    });
        //master
        Route::group(['prefix' => 'master' ,'middleware' => 'role:admin|super_admin'], function () {
            require __DIR__ . '/admin/masterUser.php';
            require __DIR__ . '/admin/masterCatalog.php';
            require __DIR__ . '/admin/masterPackage.php';
            require __DIR__ . '/admin/masterProduct.php';
            require __DIR__ . '/admin/masterSubProduct.php';
            require __DIR__ . '/admin/masterSupplier.php';
            require __DIR__ . '/admin/masterReview.php';

            Route::get('/administration/{user}', [AdministrationController::class, 'getAdministration'])->name('getAdministration');


        });

        //finance_admin, super_admin
        Route::group(['middleware' => 'role:finance_admin|super_admin'], function () {

        });

        //Transaction
        Route::group(['prefix' => 'transaction' ,'middleware' => 'role:admin|super_admin|agent'], function () {
            require __DIR__ . '/transaction/order.php';
            Route::get('/test', [TestController::class, 'index']);
        });
        Route::group(['prefix' => 'transaction' ,'middleware' => 'role:admin|super_admin'], function () {
            require __DIR__ . '/transaction/payment.php';
            require __DIR__ . '/transaction/status.php';

            //export pdf invoice payment
            Route::get('/invoice/{order}/{payment}', [ExportInvoiceController::class, 'getInvoice'])->name('getInvoice');
            //export pdf invoice order
            Route::get('/invoice/{order}', [ExportInvoiceController::class, 'getInvoiceOrder'])->name('getInvoiceOrder');
        });




        Route::group(['prefix' => 'master', 'middleware' => 'role:admin|super_admin|agent'], function () {
            //kelola sub agent
            Route::resource('sub-agent', SubAgentController::class);
        });

    //agent
    Route::group(['middleware' => ['role:agent', 'verified']], function () {
        Route::group(['middleware' => ['active']], function () {
            Route::get('/agent', [DashboardController::class, 'index'])->name('dashboard-agent');
            require __DIR__ . '/agent/profile.php';
            require __DIR__ . '/agent/changeEmailPass.php';
            require __DIR__ . '/agent/report.php';
        });

        require __DIR__ . '/agent/administration.php';
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


