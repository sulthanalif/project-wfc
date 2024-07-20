<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\Landingpage\HeaderController;
use App\Http\Controllers\Admin\Landingpage\ContactController;
use App\Http\Controllers\Admin\Landingpage\GalleryController;
use App\Http\Controllers\Admin\Landingpage\ProfileController;
use App\Http\Controllers\Admin\Landingpage\DetailProfileController;

//header
Route::get('/landing-page/header', [HeaderController::class, 'index'])->name('landingpage.header');
Route::put('/landing-page/header/{header}', [HeaderController::class, 'update'])->name('landingpage.header.update');

//profile
Route::get('/landing-page/profile', [ProfileController::class, 'index'])->name('landingpage.profile');
Route::put('/landing-page/profile/{profile}', [ProfileController::class, 'update'])->name('landingpage.profile.update');

//detail profile
Route::get('/landing-page/detail-profile', [DetailProfileController::class, 'index'])->name('landingpage.detailProfile');
Route::put('/landing-page/detail-profile/{detailProfile}', [DetailProfileController::class, 'update'])->name('landingpage.detailProfile.update');
Route::post('/landing-page/detail-profile/{detailProfile}/mission', [DetailProfileController::class, 'addMission'])->name('landingpage.detailProfile.addMission');
Route::delete('/landing-page/detail-profile/mission/{mission}', [DetailProfileController::class, 'deleteMission'])->name('landingpage.detailProfile.deleteMission');

//contact
Route::get('/landing-page/contact', [ContactController::class, 'index'])->name('landingpage.contact');
Route::put('/landing-page/contact/{contact}', [ContactController::class, 'update'])->name('landingpage.contact.update');

//gallery
Route::get('/landing-page/gallery', [GalleryController::class, 'index'])->name('landingpage.gallery');
Route::put('/landing-page/gallery/{gallery}', [GalleryController::class, 'update'])->name('landingpage.gallery.update');
Route::post('/landing-page/gallery/{gallery}/image', [GalleryController::class, 'addImage'])->name('landingpage.gallery.addImage');
Route::delete('/landing-page/gallery/image/{image}', [GalleryController::class, 'deleteImage'])->name('landingpage.gallery.deleteImage');

//review
Route::resource('landing-page/review', ReviewController::class);


