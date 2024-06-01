<?php

use App\Http\Controllers\Agent\AgentReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('report')->group(function () {
    Route::get('/agent-instalment', [AgentReportController::class, 'agentInstallment'])->name('agentInstalment');
});
