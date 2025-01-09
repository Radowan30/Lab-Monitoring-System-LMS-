<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::get('report', [ReportController::class, 'showReport']);
Route::get('/sensor-data', [ReportController::class, 'getSensorData']);
Route::get('/download-csv', [ReportController::class, 'downloadCsv'])->name('download.csv');
Route::get('/generate-report', [ReportController::class, 'generateReport'])->name('generate.report');
Route::get('/download-report-pdf', [ReportController::class, 'downloadReportPDF']);
Route::get('summary-data', [ReportController::class, 'getSummaryData']);

