<?php

use App\Http\Controllers\ReportController;
Route::get('/report-data', [ReportController::class, 'fetchChartData']);
