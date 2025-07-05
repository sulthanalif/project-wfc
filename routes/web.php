<?php

use App\Models\SubAgent;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GetImageController;
use App\Http\Controllers\SubAgentController;
use App\Http\Controllers\AccessDateController;
use App\Http\Controllers\Admin\BankOwnerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdministrationController;
// use App\Models\Administration;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Mail\NotificationController;
use App\Http\Controllers\Transaction\OrderController;
use App\Http\Controllers\Admin\DistributionController;
use App\Http\Controllers\Transaction\PaymentController;
use App\Http\Controllers\Admin\DashboardAdminController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Transaction\DetailOrderController;
use App\Http\Controllers\Admin\ExportDeliveryOrderController;
use App\Http\Controllers\CountPriceTransactionController;
use App\Http\Controllers\ReviewAgentController;
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

Route::get('/sendmail', [NotificationController::class, 'sendEmail'])->name('sendmail');
Route::get('/testview', function () {
    return view('mail.notification-acc-order');
});

Route::group(['middleware' => 'auth',], function () {
    Route::get('/agent', [DashboardController::class, 'noActive'])->name('nonactive');




    //super_admin, finance_admin, admin
    Route::group(['middleware' => 'role:super_admin|admin|finance_admin', 'active'], function () {
        Route::get('/admin', [DashboardAdminController::class, 'index'])->name('dashboard-admin');

        Route::group(['prefix' => 'master'], function () {
            Route::resource('bank-owner', BankOwnerController::class)->except(['create', 'show', 'edit']);
        });

        //export
        require __DIR__ . '/admin/export.php';

        //import
        require __DIR__ . '/admin/import.php';


        //Report
        Route::group(['prefix' => 'report'], function () {
            require __DIR__ . '/admin/report.php';
            // Route::resource('distribution', DistributionController::class);
        });

        // //Options
        // Route::group(['prefix' => 'options'], function () {
        //     //Access Date
        //     Route::get('/access-date', [AccessDateController::class, 'index'])->name('access-date');
        //     Route::post('/access-date', [AccessDateController::class, 'update'])->name('access-date.update');
        // });

        //distribution
        Route::resource('distribution', DistributionController::class);
        Route::group(['prefix' => 'distribution'], function () {
            Route::put('/{distribution}/approve', [DistributionController::class, 'approve'])->name('distribution.approve');
            Route::get('/export/{distribution}', [ExportDeliveryOrderController::class, 'getDeliveryOrder'])->name('export.deliveryOrder');
            Route::post('/export/{distribution}/printed', [ExportDeliveryOrderController::class, 'printed'])->name('export.printed');
        });
    });
    //master
    Route::group(['prefix' => 'master', 'middleware' => 'role:admin|super_admin'], function () {
        require __DIR__ . '/admin/masterUser.php';
        require __DIR__ . '/admin/masterCatalog.php';
        require __DIR__ . '/admin/masterPackage.php';
        require __DIR__ . '/admin/masterProduct.php';
        require __DIR__ . '/admin/masterSubProduct.php';
        require __DIR__ . '/admin/masterSupplier.php';
        // require __DIR__ . '/admin/masterReview.php';
        require __DIR__ . '/admin/masterLandingpage.php';
        require __DIR__ . '/admin/options.php';

        Route::get('/administration/{user}', [AdministrationController::class, 'getAdministration'])->name('getAdministration');
        Route::put('/administration/{user}', [AdministrationController::class, 'update'])->name('updateAdministration');

        //count price
        Route::get('/countPrice/{order}', [CountPriceTransactionController::class, 'count'])->name('countPrice');
        Route::get('/countAll', [CountPriceTransactionController::class, 'countAll'])->name('countAll');
    });

    //finance_admin, super_admin
    Route::group(['middleware' => 'role:finance_admin|super_admin'], function () {
        Route::prefix('finance')->group(function () {
            require __DIR__ . '/admin/finance.php';
        });
    });

    //Transaction
    Route::group(['prefix' => 'transaction', 'middleware' => 'role:admin|super_admin|agent'], function () {
        require __DIR__ . '/transaction/order.php';
        // Route::get('/test', [TestController::class, 'index']);
        //order detail
        Route::post('.order/{detail}/detail', [DetailOrderController::class, 'editDetail'])->name('order.editDetail');
        Route::post('.order/{order}/addItems', [DetailOrderController::class, 'addItems'])->name('order.addItems');

        // Payment
        Route::get('/payment-agent', [PaymentController::class, 'index'])->name('payment-agent.index');
        Route::get('/payment-agent/{user}/{order}', [PaymentController::class, 'showPaymentAgent'])->name('payment-agent.detail');
        Route::post('/payment-agent/{order}', [PaymentController::class, 'storePaymentAgent'])->name('storePaymentAgent');
        Route::put('/payment-agent/{payment}', [PaymentController::class, 'updatePaymentAgent'])->name('updatePaymentAgent');
    });
    Route::group(['prefix' => 'transaction', 'middleware' => 'role:admin|super_admin'], function () {
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
            Route::resource('/reviews', ReviewAgentController::class)->except('create', 'show', 'edit');
        });

        require __DIR__ . '/agent/administration.php';
    });

    // //option
    // Route::group(['middleware' => 'role:admin|super_admin'], function () {
    //     require __DIR__ . '/admin/options.php';
    // });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
