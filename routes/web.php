<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LabAnalyticsController;

Route::resource('customers', CustomerController::class);

Route::get('/', function () {
    return view('create');
});



Route::controller(CustomerController::class)->group(function () {
    Route::get('/customers/create', 'create')->name('customers.create');
    Route::post('/customers', 'store')->name('customers.store');
});
// Modify these routes
Route::get('/customer-analytics', [CustomerController::class, 'analytics'])->name('customer.analytics');
Route::get('/api/customers/filter', [CustomerController::class, 'analytics']); // Use analytics method for filtering
Route::get('/api/customers/{customer}', [CustomerController::class, 'show']);


// Update the route for lab analytics
Route::get('/lab-analytics', [LabAnalyticsController::class, 'index'])->name('lab.analytics');

Route::get('/customer/{id}', [LabAnalyticsController::class, 'getCustomerDetails'])->name('customer.details');
Route::delete('/customer/delete/{customerId}', [LabAnalyticsController::class, 'deleteCustomer']);