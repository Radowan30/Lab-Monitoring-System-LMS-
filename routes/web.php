<?php

use App\Http\Controllers\LabRoomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Route::match(['get', 'post'], '/dashboard/sensor1', [ProfileController::class, 'showSensorInfo'])->name('showSensorInfo');

// Route::post('/dashboard', [ProfileController::class, 'showSensorInfo'])->name('showSensorInfo');

Route::post('/dashboard/sensor1', [LabRoomController::class, 'putSensor1Info'])->name('putSensor1Info');
Route::get('/dashboard/sensor1', [LabRoomController::class, 'getSensor1Info'])->name('getSensor1Info');

Route::get('/', function () {
    return view('welcome');
});

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
});

require __DIR__ . '/auth.php';
