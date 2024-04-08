<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\AgentProfileController;

//user profile
    // Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{id}/profile', [AgentProfileController::class, 'show'])->name('users.profile');
    Route::get('/{id}/profile/edit', [AgentProfileController::class, 'edit'])->name('users.profile.edit');
    Route::put('/{id}/profile', [AgentProfileController::class, 'updateProfile'])->name('users.profile.update');
