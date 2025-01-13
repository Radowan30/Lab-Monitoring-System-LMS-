<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\LabRoomController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LabAnalyticsController;


//Routes for the admin view
Route::post('/add/user', [UserController::class, 'AddUser'])->name('AddUser');
Route::get('/adminUsers', [UserController::class, 'loadAllUsers'])->name('admin.users');
// Route::get('/add/user',[UserController::class,'loadAddUserForm']);

// Route::get('/edit/{id}',[UserController::class,'loadEditForm']);
Route::get('/delete/{id}', [UserController::class, 'deleteUser']);

Route::post('/edit/user', [UserController::class, 'EditUser'])->name('EditUser');


Route::get('/sensor1-status', [LabRoomController::class, 'checkSensor1Status']);

//Routes for everthing related to notifications in the dashboard
Route::get('/notifications/unseen-count', [NotificationController::class, 'unseenCount']);
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/{id}', [NotificationController::class, 'show']);
Route::post('/notifications/{id}/mark-as-seen', [NotificationController::class, 'markAsSeen']);

//Routes for getting the sensor data from the cache (get) and then showing it in the dashboard (post)
Route::post('/dashboard/sensor1', [LabRoomController::class, 'putSensor1Info'])->name('putSensor1Info');
Route::get('/dashboard/sensor1', [LabRoomController::class, 'getSensor1Info'])->name('getSensor1Info');


//Routes related to customer analytics subsystem
Route::resource('customers', CustomerController::class);

Route::get('/cust-form', function () {
    return view('create');
});


Route::controller(CustomerController::class)->group(function () {
    Route::get('/customers/create', 'create')->name('customers.create');
    Route::post('/customers', 'store')->name('customers.store');
});
// Modify these routes
// Route::get('/customer-analytics', [CustomerController::class, 'analytics'])->name('customer.analytics');
Route::get('/api/customers/filter', [CustomerController::class, 'analytics']); // Use analytics method for filtering
Route::get('/api/customers/{customer}', [CustomerController::class, 'show']);


// Update the route for lab analytics
Route::get('/lab-analytics', [LabAnalyticsController::class, 'index'])->name('lab.analytics');

Route::get('/customer/{id}', [LabAnalyticsController::class, 'getCustomerDetails'])->name('customer.details');
Route::delete('/customer/delete/{customerId}', [LabAnalyticsController::class, 'deleteCustomer']);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () { //here, there are two middlewares- the auth middleware checks if the user is authenticated and the verified middleware checks if the user is verified. If one of them is not satisfied, then we cant access the note routes that are specified below / is specified by the single line at the bottom


    // Route::get('/note', [NoteController::class, 'index'])->name('note.index');
// Route::get('note/create', [NoteController::class, 'create'])->name('note.create');
// Route::post('/note', [NoteController::class, 'store'])->name('note.store');
// Route::get('note/{id}', [NoteController::class, 'show'])->name('note.show');
// Route::get('note/{id}/edit', [NoteController::class, 'edit'])->name('note.edit');
// Route::put('/note/{id}', [NoteController::class, 'update'])->name('note.update');
// Route::delete('/note/{id}', [NoteController::class, 'destroy'])->name('note.destroy');

    Route::get('lab_rooms/prep-lab', [LabRoomController::class, 'show_prep_lab'])->name('lab_rooms.prep-lab');

    Route::get('lab_rooms/fetem-room', [LabRoomController::class, 'show_FETEM_room'])->name('lab_rooms.fetem-room');

    Route::get('lab_rooms/fetem-chiller', [LabRoomController::class, 'show_FETEM_chiller'])->name('lab_rooms.fetem-chiller');


    Route::get('lab_rooms/fesem-room', [LabRoomController::class, 'show_FESEM_room'])->name('lab_rooms.fesem-room');

    Route::get('lab_rooms/fesem-chiller', [LabRoomController::class, 'show_FESEM_chiller'])->name('lab_rooms.fesem-chiller');

    Route::get('/sensor-data', [LabRoomController::class, 'getSensorData'])->name('sensor.data');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    Route::post('/settings', [SettingsController::class, 'save'])->name('settings.save');

    // Route::get('/FETEM-room-sensor-data', [LabRoomController::class, 'getFETEMRoomSensorData'])->name('FETEM-room-sensor.data');

    // Route::get('/FESEM-room-sensor-data', [LabRoomController::class, 'getFESEMRoomSensorData'])->name('FESEM-room-sensor.data');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route for the user-selection page
    Route::get('/user-selection', function () {
        return view('user-selection'); // This points to your user-selection.blade.php file
    })->name('admin.choose-role');

    // Dummy Admin Page (Replace with the actual subsystem later)
    Route::get('/admin', function () {
        return view('Admin');
    })->name('admin.view');
});

require __DIR__ . '/auth.php';
