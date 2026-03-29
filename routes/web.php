<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\ServiceLogController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Offline fallback page
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// Public QR route — no login required
Route::get('/vehicle/public/{token}', [QrCodeController::class, 'publicView'])
     ->name('public.vehicle');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicle routes
    Route::resource('vehicles', VehicleController::class);
    Route::patch('vehicles/{vehicle}/mileage', [VehicleController::class, 'updateMileage'])->name('vehicles.updateMileage');

    // Fuel Log routes
    Route::get('vehicles/{vehicle}/fuel', [FuelLogController::class, 'index'])->name('fuel.index');
    Route::get('vehicles/{vehicle}/fuel/create', [FuelLogController::class, 'create'])->name('fuel.create');
    Route::post('vehicles/{vehicle}/fuel', [FuelLogController::class, 'store'])->name('fuel.store');
    Route::delete('vehicles/{vehicle}/fuel/{fuelLog}', [FuelLogController::class, 'destroy'])->name('fuel.destroy');

    // Service Log routes
    Route::get('vehicles/{vehicle}/service', [ServiceLogController::class, 'index'])->name('service.index');
    Route::get('vehicles/{vehicle}/service/create', [ServiceLogController::class, 'create'])->name('service.create');
    Route::post('vehicles/{vehicle}/service', [ServiceLogController::class, 'store'])->name('service.store');
    Route::delete('vehicles/{vehicle}/service/{serviceLog}', [ServiceLogController::class, 'destroy'])->name('service.destroy');

    // Suggestion Engine
    Route::get('vehicles/{vehicle}/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');

    // QR Code
    Route::get('vehicles/{vehicle}/qrcode', [QrCodeController::class, 'show'])->name('qrcode.show');

    // Parts Verification
    Route::get('/parts', [PartsController::class, 'index'])->name('parts.index');

    // Garages
    Route::get('/garages', [GarageController::class, 'index'])->name('garages.index');
    Route::get('/garages/create', [GarageController::class, 'create'])->name('garages.create');
    Route::post('/garages', [GarageController::class, 'store'])->name('garages.store');
    Route::get('/garage/dashboard', [GarageController::class, 'dashboard'])->name('garage.dashboard');
    Route::post('/garage/bookings/{booking}', [GarageController::class, 'updateBooking'])->name('garage.updateBooking');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/garages/{garage}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/garages/{garage}/book', [BookingController::class, 'store'])->name('bookings.store');

    // Booking invoice
    Route::patch('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');

    // Admin routes — protected by admin middleware
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{user}/make-admin', [AdminController::class, 'makeAdmin'])->name('admin.makeAdmin');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    });
});

require __DIR__.'/auth.php';