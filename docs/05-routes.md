# Routes

Dihasilkan otomatis. Jangan edit tabel ini dengan tangan. Setelah mengubah file di `routes/`, jalankan:

```bash
php artisan route:list --except-vendor --json > /tmp/routes.json
python3 docs/tools/gen_routes.py /tmp/routes.json > docs/05-routes.md
```

`routes/web.php` memuat file per modul dari `routes/admin/`, `routes/agent/`, `routes/transaction/`, dan `routes/landingpage/`. Kolom Akses berisi role Spatie; `a + b` berarti route ada di dua grup role sekaligus dan user harus lolos keduanya.

Total 279 route.

## `/`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `/` | landing-page | `LandingpageController@index` | publik |

## `admin`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `admin` | dashboard-admin | `Admin\DashboardAdminController@index` | super_admin, admin, finance_admin |

## `agent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `agent` | dashboard-agent | `Agent\DashboardController@index` | agent |

## `api`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `api/user` |  | `Closure` | login |

## `catalogs-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `catalogs-product` | catalogs-product | `LandingpageController@catalogs` | publik |

## `company-profile`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `company-profile` | company-profile | `LandingpageController@profile` | publik |

## `distribution`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `distribution` | distribution.index | `Admin\DistributionController@index` | super_admin, admin, finance_admin |
| POST | `distribution` | distribution.store | `Admin\DistributionController@store` | super_admin, admin, finance_admin |
| GET | `distribution/create` | distribution.create | `Admin\DistributionController@create` | super_admin, admin, finance_admin |
| GET | `distribution/export/{distribution}` | export.deliveryOrder | `Admin\ExportDeliveryOrderController@getDeliveryOrder` | super_admin, admin, finance_admin |
| POST | `distribution/export/{distribution}/printed` | export.printed | `Admin\ExportDeliveryOrderController@printed` | super_admin, admin, finance_admin |
| DELETE | `distribution/{distribution}` | distribution.destroy | `Admin\DistributionController@destroy` | super_admin, admin, finance_admin |
| GET | `distribution/{distribution}` | distribution.show | `Admin\DistributionController@show` | super_admin, admin, finance_admin |
| PUT|PATCH | `distribution/{distribution}` | distribution.update | `Admin\DistributionController@update` | super_admin, admin, finance_admin |
| PUT | `distribution/{distribution}/approve` | distribution.approve | `Admin\DistributionController@approve` | super_admin, admin, finance_admin |
| GET | `distribution/{distribution}/edit` | distribution.edit | `Admin\DistributionController@edit` | super_admin, admin, finance_admin |

## `download`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `download/{file}` | download.file | `DownloadFileController@download` | super_admin, admin, finance_admin |

## `email`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `email` | email.index | `Auth\UpdateEmailPasswordController@changeEmail` | agent |
| POST | `email/checking` | email.checking | `Auth\UpdateEmailPasswordController@checkEmail` | agent |
| POST | `email/resend` | verification.resend | `Auth\VerificationController@resend` | login |
| GET | `email/verify` | verification.notice | `Auth\VerificationController@show` | login |
| GET | `email/verify/{id}/{hash}` | verification.verify | `Auth\VerificationController@verify` | login |
| PUT | `email/{user}` | email.update | `Auth\UpdateEmailPasswordController@updateEmail` | agent |

## `export-agent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `export-agent` | export.agent | `Admin\UserController@export` | super_admin, admin, finance_admin |

## `export-package`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `export-package` | export.package | `Admin\PackageController@export` | super_admin, admin, finance_admin |

## `export-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `export-product` | export.product | `Admin\ProductController@export` | super_admin, admin, finance_admin |

## `export-sub-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `export-sub-product` | export.sub-product | `Admin\SubProductController@export` | super_admin, admin, finance_admin |

## `export-subagent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `export-subagent` | export.subAgent | `SubAgentController@export` | super_admin, admin, finance_admin |

## `finance/cash-flow`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/cash-flow` | cash-flow.index | `Admin\CashFlowController@index` | finance_admin, super_admin |
| GET | `finance/cash-flow/create` | cash-flow.create | `Admin\CashFlowController@create` | finance_admin, super_admin |
| POST | `finance/cash-flow/transfer` | cash-flow.store | `Admin\CashFlowController@transferCash` | finance_admin, super_admin |

## `finance/changePaymentVerify`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| PUT | `finance/changePaymentVerify/{payment}` | loan.verifyPayment | `Admin\LoanController@verifyPayment` | finance_admin, super_admin |

## `finance/export-income`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/export-income` | income.export | `Admin\IncomeController@export` | finance_admin, super_admin |

## `finance/export-loan`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/export-loan` | loan.export | `Admin\LoanController@export` | finance_admin, super_admin |

## `finance/export-spending`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/export-spending` | spending.export | `Admin\SpendingController@export` | finance_admin, super_admin |

## `finance/income`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/income` | income.index | `Admin\IncomeController@index` | finance_admin, super_admin |
| POST | `finance/income` | income.store | `Admin\IncomeController@store` | finance_admin, super_admin |
| GET | `finance/income/create` | income.create | `Admin\IncomeController@create` | finance_admin, super_admin |
| DELETE | `finance/income/{income}` | income.destroy | `Admin\IncomeController@destroy` | finance_admin, super_admin |
| GET | `finance/income/{income}` | income.show | `Admin\IncomeController@show` | finance_admin, super_admin |
| PUT|PATCH | `finance/income/{income}` | income.update | `Admin\IncomeController@update` | finance_admin, super_admin |
| GET | `finance/income/{income}/edit` | income.edit | `Admin\IncomeController@edit` | finance_admin, super_admin |

## `finance/loan`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/loan` | loan.index | `Admin\LoanController@index` | finance_admin, super_admin |
| POST | `finance/loan` | loan.store | `Admin\LoanController@store` | finance_admin, super_admin |
| DELETE | `finance/loan/{loan}` | loan.destroy | `Admin\LoanController@destroy` | finance_admin, super_admin |
| GET | `finance/loan/{loan}` | loan.show | `Admin\LoanController@show` | finance_admin, super_admin |
| PUT|PATCH | `finance/loan/{loan}` | loan.update | `Admin\LoanController@update` | finance_admin, super_admin |
| POST | `finance/loan/{loan}/payment` | loan.storePayment | `Admin\LoanController@storePayment` | finance_admin, super_admin |
| DELETE | `finance/loan/{loan}/payment/{payment}` | loan.deletePayment | `Admin\LoanController@deletePayment` | finance_admin, super_admin |

## `finance/procurement`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/procurement` | procurement.index | `ProcurementController@index` | finance_admin, super_admin |
| POST | `finance/procurement` | procurement.store | `ProcurementController@store` | finance_admin, super_admin |
| GET | `finance/procurement/create` | procurement.create | `ProcurementController@create` | finance_admin, super_admin |
| DELETE | `finance/procurement/{procurement}` | procurement.destroy | `ProcurementController@destroy` | finance_admin, super_admin |
| GET | `finance/procurement/{procurement}` | procurement.show | `ProcurementController@show` | finance_admin, super_admin |
| PUT|PATCH | `finance/procurement/{procurement}` | procurement.update | `ProcurementController@update` | finance_admin, super_admin |
| GET | `finance/procurement/{procurement}/edit` | procurement.edit | `ProcurementController@edit` | finance_admin, super_admin |

## `finance/spending`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/spending` | spending.index | `Admin\SpendingController@index` | finance_admin, super_admin |
| POST | `finance/spending` | spending.store | `Admin\SpendingController@store` | finance_admin, super_admin |
| GET | `finance/spending/create` | spending.create | `Admin\SpendingController@create` | finance_admin, super_admin |
| DELETE | `finance/spending/{spending}` | spending.destroy | `Admin\SpendingController@destroy` | finance_admin, super_admin |
| GET | `finance/spending/{spending}` | spending.show | `Admin\SpendingController@show` | finance_admin, super_admin |
| PUT|PATCH | `finance/spending/{spending}` | spending.update | `Admin\SpendingController@update` | finance_admin, super_admin |
| GET | `finance/spending/{spending}/edit` | spending.edit | `Admin\SpendingController@edit` | finance_admin, super_admin |

## `finance/spending-type`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `finance/spending-type` | type-spending.index | `Admin\SpendingTypeController@index` | finance_admin, super_admin |
| POST | `finance/spending-type` | type-spending.storeOrUpdate | `Admin\SpendingTypeController@storeOrUpdate` | finance_admin, super_admin |
| DELETE | `finance/spending-type/{spendingType}` | type-spending.destroy | `Admin\SpendingTypeController@destroy` | finance_admin, super_admin |

## `home`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `home` | home | `HomeController@index` | login |

## `import-agent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `import-agent` | import.agent | `Admin\UserController@import` | super_admin, admin, finance_admin |

## `import-package`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `import-package` | import.package | `Admin\PackageController@import` | super_admin, admin, finance_admin |

## `import-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `import-product` | import.product | `Admin\ProductController@import` | super_admin, admin, finance_admin |

## `import-sub-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `import-sub-product` | import.sub-product | `Admin\SubProductController@import` | super_admin, admin, finance_admin |

## `login`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `login` | login | `Auth\LoginController@showLoginForm` | login |
| POST | `login` |  | `Auth\LoginController@login` | login |

## `logout`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `logout` | logout | `Auth\LoginController@logout` | publik |

## `master/administration`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/administration/{user}` | getAdministration | `AdministrationController@getAdministration` | admin, super_admin |
| PUT | `master/administration/{user}` | updateAdministration | `AdministrationController@update` | admin, super_admin, agent |

## `master/bank-owner`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/bank-owner` | bank-owner.index | `Admin\BankOwnerController@index` | super_admin, admin, finance_admin |
| POST | `master/bank-owner` | bank-owner.store | `Admin\BankOwnerController@store` | super_admin, admin, finance_admin |
| DELETE | `master/bank-owner/{bank_owner}` | bank-owner.destroy | `Admin\BankOwnerController@destroy` | super_admin, admin, finance_admin |
| PUT|PATCH | `master/bank-owner/{bank_owner}` | bank-owner.update | `Admin\BankOwnerController@update` | super_admin, admin, finance_admin |

## `master/catalog`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/catalog` | catalog.index | `Admin\CatalogController@index` | admin, super_admin |
| POST | `master/catalog` | catalog.store | `Admin\CatalogController@store` | admin, super_admin |
| GET | `master/catalog/create` | catalog.create | `Admin\CatalogController@create` | admin, super_admin |
| DELETE | `master/catalog/{catalog}` | catalog.destroy | `Admin\CatalogController@destroy` | admin, super_admin |
| GET | `master/catalog/{catalog}` | catalog.show | `Admin\CatalogController@show` | admin, super_admin |
| PUT|PATCH | `master/catalog/{catalog}` | catalog.update | `Admin\CatalogController@update` | admin, super_admin |
| GET | `master/catalog/{catalog}/edit` | catalog.edit | `Admin\CatalogController@edit` | admin, super_admin |

## `master/commissions`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/commissions` | commissions.index | `Admin\CommissionController@index` | admin, super_admin |
| POST | `master/commissions` | commissions.store | `Admin\CommissionController@store` | admin, super_admin |
| GET | `master/commissions/archive` | commissions.archive | `Admin\CommissionController@archive` | admin, super_admin |
| GET | `master/commissions/archive/{commission}` | commissions.archive.show | `Admin\CommissionController@archiveShow` | admin, super_admin |
| GET | `master/commissions/create` | commissions.create | `Admin\CommissionController@create` | admin, super_admin |
| DELETE | `master/commissions/{commission}` | commissions.destroy | `Admin\CommissionController@destroy` | admin, super_admin |
| GET | `master/commissions/{commission}` | commissions.show | `Admin\CommissionController@show` | admin, super_admin |
| PUT|PATCH | `master/commissions/{commission}` | commissions.update | `Admin\CommissionController@update` | admin, super_admin |
| GET | `master/commissions/{commission}/edit` | commissions.edit | `Admin\CommissionController@edit` | admin, super_admin |

## `master/countAll`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/countAll` | countAll | `CountPriceTransactionController@countAll` | admin, super_admin |

## `master/countPrice`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/countPrice/{order}` | countPrice | `CountPriceTransactionController@count` | admin, super_admin |

## `master/landing-page`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/landing-page/benefit` | landingpage.benefit | `Admin\Landingpage\BenefitController@index` | admin, super_admin |
| PUT | `master/landing-page/benefit/{benefit}` | landingpage.benefit.update | `Admin\Landingpage\BenefitController@update` | admin, super_admin |
| GET | `master/landing-page/contact` | landingpage.contact | `Admin\Landingpage\ContactController@index` | admin, super_admin |
| DELETE | `master/landing-page/contact/number/{contactNumber}` | landingpage.contact.deleteNumber | `Admin\Landingpage\ContactController@deleteNumber` | admin, super_admin |
| PUT | `master/landing-page/contact/{contact}` | landingpage.contact.update | `Admin\Landingpage\ContactController@update` | admin, super_admin |
| POST | `master/landing-page/contact/{contact}/addOrUpdateNumber` | landingpage.contact.addOrUpdateNumber | `Admin\Landingpage\ContactController@addOrUpdateNumber` | admin, super_admin |
| GET | `master/landing-page/detail-profile` | landingpage.detailProfile | `Admin\Landingpage\DetailProfileController@index` | admin, super_admin |
| DELETE | `master/landing-page/detail-profile/mission/{mission}` | landingpage.detailProfile.deleteMission | `Admin\Landingpage\DetailProfileController@deleteMission` | admin, super_admin |
| PUT | `master/landing-page/detail-profile/{detailProfile}` | landingpage.detailProfile.update | `Admin\Landingpage\DetailProfileController@update` | admin, super_admin |
| POST | `master/landing-page/detail-profile/{detailProfile}/mission` | landingpage.detailProfile.addMission | `Admin\Landingpage\DetailProfileController@addMission` | admin, super_admin |
| GET | `master/landing-page/gallery` | landingpage.gallery | `Admin\Landingpage\GalleryController@index` | admin, super_admin |
| DELETE | `master/landing-page/gallery/image/{image}` | landingpage.gallery.deleteImage | `Admin\Landingpage\GalleryController@deleteImage` | admin, super_admin |
| PUT | `master/landing-page/gallery/{gallery}` | landingpage.gallery.update | `Admin\Landingpage\GalleryController@update` | admin, super_admin |
| POST | `master/landing-page/gallery/{gallery}/image` | landingpage.gallery.addImage | `Admin\Landingpage\GalleryController@addImage` | admin, super_admin |
| GET | `master/landing-page/header` | landingpage.header | `Admin\Landingpage\HeaderController@index` | admin, super_admin |
| PUT | `master/landing-page/header/{header}` | landingpage.header.update | `Admin\Landingpage\HeaderController@update` | admin, super_admin |
| GET | `master/landing-page/profile` | landingpage.profile | `Admin\Landingpage\ProfileController@index` | admin, super_admin |
| PUT | `master/landing-page/profile/{profile}` | landingpage.profile.update | `Admin\Landingpage\ProfileController@update` | admin, super_admin |
| GET | `master/landing-page/review` | landingpage.review | `Admin\Landingpage\ReviewPageController@index` | admin, super_admin |
| DELETE | `master/landing-page/review/deleteReview/{review}` | landingpage.review.deleteReview | `Admin\Landingpage\ReviewPageController@deleteReview` | admin, super_admin |
| POST | `master/landing-page/review/publish` | publishReview | `Admin\Landingpage\ReviewPageController@publishReview` | admin, super_admin |
| PUT | `master/landing-page/review/updateReview/{review}` | landingpage.review.updateReview | `Admin\Landingpage\ReviewPageController@updateReview` | admin, super_admin |
| POST | `master/landing-page/review/{review_page}/addReview` | landingpage.review.addReview | `Admin\Landingpage\ReviewPageController@addReview` | admin, super_admin |
| PUT | `master/landing-page/review/{review}` | landingpage.review.update | `Admin\Landingpage\ReviewPageController@update` | admin, super_admin |

## `master/options`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/options` | options.index | `OptionController@index` | admin, super_admin |
| POST | `master/options/period` | addOrUpdatePeriod | `PeriodController@addOrUpdatePeriod` | admin, super_admin |
| POST | `master/options/period/activate` | activatePeriod | `PeriodController@activatePeriod` | admin, super_admin |
| DELETE | `master/options/period/{period}` | deletePeriod | `PeriodController@deletePeriod` | admin, super_admin |

## `master/package`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/package` | package.index | `Admin\PackageController@index` | admin, super_admin |
| POST | `master/package` | package.store | `Admin\PackageController@store` | admin, super_admin |
| GET | `master/package/archive` | package.archive | `Admin\PackageController@archive` | admin, super_admin |
| GET | `master/package/archive/{package}` | package.archiveShow | `Admin\PackageController@archiveShow` | admin, super_admin |
| GET | `master/package/create` | package.create | `Admin\PackageController@create` | admin, super_admin |
| DELETE | `master/package/{package}` | package.destroy | `Admin\PackageController@destroy` | admin, super_admin |
| GET | `master/package/{package}` | package.show | `Admin\PackageController@show` | admin, super_admin |
| PUT|PATCH | `master/package/{package}` | package.update | `Admin\PackageController@update` | admin, super_admin |
| GET | `master/package/{package}/edit` | package.edit | `Admin\PackageController@edit` | admin, super_admin |

## `master/product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/product` | product.index | `Admin\ProductController@index` | admin, super_admin |
| POST | `master/product` | product.store | `Admin\ProductController@store` | admin, super_admin |
| GET | `master/product/archive` | product.archive | `Admin\ProductController@archive` | admin, super_admin |
| GET | `master/product/archive/{product}` | product.archive.show | `Admin\ProductController@archiveShow` | admin, super_admin |
| GET | `master/product/create` | product.create | `Admin\ProductController@create` | admin, super_admin |
| DELETE | `master/product/{product}` | product.destroy | `Admin\ProductController@destroy` | admin, super_admin |
| GET | `master/product/{product}` | product.show | `Admin\ProductController@show` | admin, super_admin |
| PUT|PATCH | `master/product/{product}` | product.update | `Admin\ProductController@update` | admin, super_admin |
| GET | `master/product/{product}/edit` | product.edit | `Admin\ProductController@edit` | admin, super_admin |
| POST | `master/product/{product}/sub-product` | product.addSubProduct | `Admin\ProductController@addSubProduct` | admin, super_admin |
| DELETE | `master/product/{product}/sub-product/{productSubProduct}` | product.destroySub | `Admin\ProductController@destroySub` | admin, super_admin |

## `master/rewards`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/rewards` | rewards.index | `RewardController@index` | admin, super_admin |
| POST | `master/rewards` | rewards.store | `RewardController@store` | admin, super_admin |
| GET | `master/rewards/create` | rewards.create | `RewardController@create` | admin, super_admin |
| DELETE | `master/rewards/{reward}` | rewards.destroy | `RewardController@destroy` | admin, super_admin |
| GET | `master/rewards/{reward}` | rewards.show | `RewardController@show` | admin, super_admin |
| PUT|PATCH | `master/rewards/{reward}` | rewards.update | `RewardController@update` | admin, super_admin |
| GET | `master/rewards/{reward}/edit` | rewards.edit | `RewardController@edit` | admin, super_admin |

## `master/sub-agent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/sub-agent` | sub-agent.index | `SubAgentController@index` | admin, super_admin, agent |
| POST | `master/sub-agent` | sub-agent.store | `SubAgentController@store` | admin, super_admin, agent |
| GET | `master/sub-agent/create` | sub-agent.create | `SubAgentController@create` | admin, super_admin, agent |
| DELETE | `master/sub-agent/{sub_agent}` | sub-agent.destroy | `SubAgentController@destroy` | admin, super_admin, agent |
| GET | `master/sub-agent/{sub_agent}` | sub-agent.show | `SubAgentController@show` | admin, super_admin, agent |
| PUT|PATCH | `master/sub-agent/{sub_agent}` | sub-agent.update | `SubAgentController@update` | admin, super_admin, agent |
| GET | `master/sub-agent/{sub_agent}/edit` | sub-agent.edit | `SubAgentController@edit` | admin, super_admin, agent |

## `master/sub-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/sub-product` | sub-product.index | `Admin\SubProductController@index` | admin, super_admin |
| POST | `master/sub-product` | sub-product.store | `Admin\SubProductController@store` | admin, super_admin |
| GET | `master/sub-product/create` | sub-product.create | `Admin\SubProductController@create` | admin, super_admin |
| DELETE | `master/sub-product/{sub_product}` | sub-product.destroy | `Admin\SubProductController@destroy` | admin, super_admin |
| GET | `master/sub-product/{sub_product}` | sub-product.show | `Admin\SubProductController@show` | admin, super_admin |
| PUT|PATCH | `master/sub-product/{sub_product}` | sub-product.update | `Admin\SubProductController@update` | admin, super_admin |
| GET | `master/sub-product/{sub_product}/edit` | sub-product.edit | `Admin\SubProductController@edit` | admin, super_admin |

## `master/supplier`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/supplier` | supplier.index | `Admin\SupplierController@index` | admin, super_admin |
| POST | `master/supplier` | supplier.store | `Admin\SupplierController@store` | admin, super_admin |
| GET | `master/supplier/create` | supplier.create | `Admin\SupplierController@create` | admin, super_admin |
| DELETE | `master/supplier/{supplier}` | supplier.destroy | `Admin\SupplierController@destroy` | admin, super_admin |
| GET | `master/supplier/{supplier}` | supplier.show | `Admin\SupplierController@show` | admin, super_admin |
| PUT|PATCH | `master/supplier/{supplier}` | supplier.update | `Admin\SupplierController@update` | admin, super_admin |
| GET | `master/supplier/{supplier}/edit` | supplier.edit | `Admin\SupplierController@edit` | admin, super_admin |

## `master/tutorial`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/tutorial` | tutorial.index | `TutorialController@index` | super_admin |
| POST | `master/tutorial` | tutorial.store | `TutorialController@store` | super_admin |
| GET | `master/tutorial/create` | tutorial.create | `TutorialController@create` | super_admin |
| DELETE | `master/tutorial/{tutorial}` | tutorial.destroy | `TutorialController@destroy` | super_admin |
| GET | `master/tutorial/{tutorial}` | tutorial.show | `TutorialController@show` | super_admin |
| PUT|PATCH | `master/tutorial/{tutorial}` | tutorial.update | `TutorialController@update` | super_admin |
| GET | `master/tutorial/{tutorial}/edit` | tutorial.edit | `TutorialController@edit` | super_admin |

## `master/user`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/user` | user.index | `Admin\UserController@index` | admin, super_admin |
| POST | `master/user` | user.store | `Admin\UserController@store` | admin, super_admin |
| PUT | `master/user/access/{user}` | user.status.access | `Admin\UserController@changeStatusAccess` | admin, super_admin |
| GET | `master/user/create` | user.create | `Admin\UserController@create` | admin, super_admin |
| POST | `master/user/role/{user}` | user.role | `Admin\UserController@changeRole` | admin, super_admin |
| DELETE | `master/user/{user}` | user.destroy | `Admin\UserController@destroy` | admin, super_admin |
| GET | `master/user/{user}` | user.show | `Admin\UserController@show` | admin, super_admin |
| PUT|PATCH | `master/user/{user}` | user.update | `Admin\UserController@update` | admin, super_admin |
| GET | `master/user/{user}/edit` | user.edit | `Admin\UserController@edit` | admin, super_admin |
| PUT | `master/user/{user}/status` | user.status | `Admin\UserController@changeStatus` | admin, super_admin |

## `master/user-admin`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `master/user-admin` | get.admin | `Admin\UserController@getAdmin` | admin, super_admin |

## `new-agent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `new-agent` | nonactive | `AdministrationController@index` | agent |
| POST | `new-agent` | upload | `AdministrationController@store` | agent |

## `password`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `password` | admin.password.change | `Auth\UpdateEmailPasswordController@changePassword` | agent |
| GET | `password/confirm` | password.confirm | `Auth\ConfirmPasswordController@showConfirmForm` | login |
| POST | `password/confirm` |  | `Auth\ConfirmPasswordController@confirm` | login |
| POST | `password/email` | password.email | `Auth\ForgotPasswordController@sendResetLinkEmail` | publik |
| GET | `password/reset` | password.request | `Auth\ForgotPasswordController@showLinkRequestForm` | publik |
| POST | `password/reset` | password.update | `Auth\ResetPasswordController@reset` | publik |
| GET | `password/reset/{token}` | password.reset | `Auth\ResetPasswordController@showResetForm` | publik |
| PUT | `password/{user}` | admin.password.update | `Auth\UpdateEmailPasswordController@updatePassword` | agent |

## `register`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `register` | register | `Auth\RegisterController@showRegistrationForm` | login |
| POST | `register` |  | `Auth\RegisterController@register` | login |

## `report/agent-instalment`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/agent-instalment` | agentInstalment | `Agent\AgentReportController@agentInstallment` | agent |

## `report/agent-order`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/agent-order` | ragentOrder | `Admin\ReportController@agentOrder` | super_admin, admin, finance_admin |

## `report/agent-product-detail`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/agent-product-detail/{productId}` | ragentProductDetail | `Admin\ReportController@agentProductDetail` | super_admin, admin, finance_admin |

## `report/agent-reward`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/agent-reward` | ragentReward | `Admin\ReportController@agentReward` | super_admin, admin, finance_admin |

## `report/daily`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/daily` | daily | `Admin\ReportController@daily` | super_admin, admin, finance_admin |

## `report/instalment`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/instalment` | instalment | `Admin\ReportController@instalment` | super_admin, admin, finance_admin |

## `report/product-detail`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/product-detail` | rproductDetail | `Admin\ReportController@productDetail` | super_admin, admin, finance_admin |

## `report/requirement`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/requirement` | requirement | `Admin\ReportController@requirement` | super_admin, admin, finance_admin |

## `report/stock-sub-product`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/stock-sub-product` | stockSubProduct | `Admin\ReportController@stockSubProduct` | super_admin, admin, finance_admin |

## `report/total-deposit`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `report/total-deposit` | totalDeposit | `Admin\ReportController@totalDeposit` | super_admin, admin, finance_admin |

## `reviews`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `reviews` | reviews.index | `ReviewAgentController@index` | agent |
| POST | `reviews` | reviews.store | `ReviewAgentController@store` | agent |
| DELETE | `reviews/{review}` | reviews.destroy | `ReviewAgentController@destroy` | agent |
| PUT|PATCH | `reviews/{review}` | reviews.update | `ReviewAgentController@update` | agent |

## `sendmail`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `sendmail` | sendmail | `Mail\NotificationController@sendEmail` | publik |

## `storage`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `storage/images/{path}/{imageName}` | getImage | `GetImageController@displayImage` | publik |

## `testview`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `testview` |  | `Closure` | publik |

## `transaction/.order`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `transaction/.order/{detail}/detail` | order.editDetail | `Transaction\DetailOrderController@editDetail` | admin, super_admin, agent, finance_admin |
| POST | `transaction/.order/{order}/addItems` | order.addItems | `Transaction\DetailOrderController@addItems` | admin, super_admin, agent, finance_admin |

## `transaction/acc`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `transaction/acc/{order}` | order.accOrder | `Transaction\OrderController@accOrder` | admin, super_admin, finance_admin, agent |

## `transaction/accPayment`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `transaction/accPayment/{payment}/{order}` | accPayment | `Transaction\PaymentController@accPayment` | admin, super_admin, finance_admin, agent |

## `transaction/bulk-convert-to-safe-point`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `transaction/bulk-convert-to-safe-point` | order.bulkConvertToSafePoint | `Transaction\OrderController@bulkConvertToSafePoint` | admin, super_admin, finance_admin, agent |

## `transaction/changeOrderStatus`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `transaction/changeOrderStatus/{order}` | order.changeOrderStatus | `Transaction\OrderController@changeOrderStatus` | admin, super_admin, finance_admin, agent |

## `transaction/changePaymentStatus`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| PUT | `transaction/changePaymentStatus/{payment}` | changePaymentStatus | `Transaction\PaymentController@changePaymentStatus` | admin, super_admin, finance_admin, agent |

## `transaction/changePaymentVerify`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| PUT | `transaction/changePaymentVerify/{payment}` | changePaymentVerify | `Transaction\PaymentController@changePaymentVerify` | admin, super_admin, finance_admin, agent |

## `transaction/invoice`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `transaction/invoice/{order}` | getInvoiceOrder | `Transaction\ExportInvoiceController@getInvoiceOrder` | admin, super_admin, finance_admin, agent |
| GET | `transaction/invoice/{order}/{payment}` | getInvoice | `Transaction\ExportInvoiceController@getInvoice` | admin, super_admin, finance_admin, agent |

## `transaction/order`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `transaction/order` | order.index | `Transaction\OrderController@index` | admin, super_admin, agent, finance_admin |
| POST | `transaction/order` | order.store | `Transaction\OrderController@store` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/archive` | order.archive | `Transaction\OrderController@archive` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/archive/{order}` | order.archive.show | `Transaction\OrderController@archiveShow` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/create` | order.create | `Transaction\OrderController@create` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/full` | order.full.index | `Transaction\OrderFullController@index` | admin, super_admin, agent, finance_admin |
| POST | `transaction/order/full` | order.full.store | `Transaction\OrderFullController@store` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/full/archive` | order.full.archive | `Transaction\OrderFullController@archive` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/full/archive/{order}` | order.full.archive.show | `Transaction\OrderFullController@archiveShow` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/full/create` | order.full.create | `Transaction\OrderFullController@create` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/full/{order}` | order.full.show | `Transaction\OrderFullController@show` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/titik-aman` | order.aman.index | `Transaction\OrderAmanController@index` | admin, super_admin, agent, finance_admin |
| POST | `transaction/order/titik-aman` | order.aman.store | `Transaction\OrderAmanController@store` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/titik-aman/archive` | order.aman.archive | `Transaction\OrderAmanController@archive` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/titik-aman/archive/{order}` | order.aman.archive.show | `Transaction\OrderAmanController@archiveShow` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/titik-aman/create` | order.aman.create | `Transaction\OrderAmanController@create` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/titik-aman/{order}` | order.aman.show | `Transaction\OrderAmanController@show` | admin, super_admin, agent, finance_admin |
| DELETE | `transaction/order/{order}` | order.destroy | `Transaction\OrderController@destroy` | admin, super_admin, agent, finance_admin |
| GET | `transaction/order/{order}` | order.show | `Transaction\OrderController@show` | admin, super_admin, agent, finance_admin |

## `transaction/orderStats`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `transaction/orderStats` | getOrderStats | `Transaction\OrderController@getOrderStats` | admin, super_admin, finance_admin, agent |

## `transaction/payment`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `transaction/payment` | payment.index | `Transaction\PaymentController@index` | finance_admin, admin, super_admin |
| GET | `transaction/payment/check/{order}` | payment.check | `Transaction\PaymentController@check` | finance_admin, admin, super_admin |
| POST | `transaction/payment/{order}` | storePayment | `Transaction\PaymentController@storePayment` | finance_admin, admin, super_admin |
| DELETE | `transaction/payment/{payment}` | payment.destroy | `Transaction\PaymentController@destroy` | finance_admin, admin, super_admin |
| PUT | `transaction/payment/{payment}` | updatePayment | `Transaction\PaymentController@updatePayment` | finance_admin, admin, super_admin |
| GET | `transaction/payment/{user}` | payment.show | `Transaction\PaymentController@show` | finance_admin, admin, super_admin |
| GET | `transaction/payment/{user}/{order}` | payment.detail | `Transaction\PaymentController@showPayment` | finance_admin, admin, super_admin |

## `transaction/payment-agent`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `transaction/payment-agent` | payment-agent.index | `Transaction\PaymentController@index` | admin, super_admin, agent, finance_admin |
| POST | `transaction/payment-agent/{order}` | storePaymentAgent | `Transaction\PaymentController@storePaymentAgent` | admin, super_admin, agent, finance_admin |
| PUT | `transaction/payment-agent/{payment}` | updatePaymentAgent | `Transaction\PaymentController@updatePaymentAgent` | admin, super_admin, agent, finance_admin |
| GET | `transaction/payment-agent/{user}/{order}` | payment-agent.detail | `Transaction\PaymentController@showPaymentAgent` | admin, super_admin, agent, finance_admin |

## `transaction/rejectPayment`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| POST | `transaction/rejectPayment/{payment}/{order}` | rejectPayment | `Transaction\PaymentController@rejectPayment` | admin, super_admin, finance_admin, agent |

## `transaction/return`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `transaction/return` | return.index | `Transaction\ProductReturnController@index` | admin, super_admin, agent, finance_admin |
| POST | `transaction/return` | return.store | `Transaction\ProductReturnController@store` | admin, super_admin, agent, finance_admin |
| GET | `transaction/return/create` | return.create | `Transaction\ProductReturnController@create` | admin, super_admin, agent, finance_admin |
| DELETE | `transaction/return/{return}` | return.destroy | `Transaction\ProductReturnController@destroy` | admin, super_admin, agent, finance_admin |
| GET | `transaction/return/{return}` | return.show | `Transaction\ProductReturnController@show` | admin, super_admin, agent, finance_admin |
| PUT|PATCH | `transaction/return/{return}` | return.update | `Transaction\ProductReturnController@update` | admin, super_admin, agent, finance_admin |
| POST | `transaction/return/{return}/add-item` | return.item.create | `Transaction\ProductReturnController@addItem` | admin, super_admin, agent, finance_admin |
| PUT | `transaction/return/{return}/change-status` | return.changeStatus | `Transaction\ProductReturnController@changeStatus` | admin, super_admin, agent, finance_admin |
| GET | `transaction/return/{return}/edit` | return.edit | `Transaction\ProductReturnController@edit` | admin, super_admin, agent, finance_admin |
| DELETE | `transaction/return/{return}/{item}/delete` | return.item.destroy | `Transaction\ProductReturnController@destroyItem` | admin, super_admin, agent, finance_admin |
| PUT | `transaction/return/{return}/{item}/edit-item` | return.item.update | `Transaction\ProductReturnController@editItem` | admin, super_admin, agent, finance_admin |

## `tutorial`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `tutorial` | tutorial.page | `TutorialController@tutorialPage` | admin, admin_finance, agent |

## `waiting`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `waiting` | waiting | `AdministrationController@waiting` | agent |

## `{id}`

| Method | URI | Name | Controller@method | Akses |
|---|---|---|---|---|
| GET | `{id}/profile` | users.profile | `Agent\AgentProfileController@show` | agent |
| PUT | `{id}/profile` | users.profile.update | `Agent\AgentProfileController@updateProfile` | agent |
| GET | `{id}/profile/edit` | users.profile.edit | `Agent\AgentProfileController@edit` | agent |

