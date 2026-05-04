<?php

use App\Http\Controllers\LocaleController;
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
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/dashboard', function () {
    $latestBooking = auth()->user()->bookings()
        ->with('garage', 'vehicle')
        ->latest('booking_date')
        ->first();
    return view('dashboard', compact('latestBooking'));
})->middleware(['auth', 'verified', 'vehicle.owner'])->name('dashboard');

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
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // Vehicle owner routes — restricted to vehicle_owner + admin
    Route::middleware('vehicle.owner')->group(function () {
        // Vehicles — specific routes BEFORE resource to avoid {vehicle} capture
        Route::get('vehicles/archived', [VehicleController::class, 'archived'])->name('vehicles.archived');
        Route::resource('vehicles', VehicleController::class);
        Route::patch('vehicles/{vehicle}/mileage',   [VehicleController::class, 'updateMileage'])->name('vehicles.updateMileage');
        Route::patch('vehicles/{vehicle}/notes',     [VehicleController::class, 'updateNotes'])->name('vehicles.updateNotes');
        Route::patch('vehicles/{vehicle}/photo',     [VehicleController::class, 'updatePhoto'])->name('vehicles.updatePhoto');
        Route::delete('vehicles/{vehicle}/photo',    [VehicleController::class, 'removePhoto'])->name('vehicles.removePhoto');
        Route::patch('vehicles/{vehicle}/documents', [VehicleController::class, 'updateDocuments'])->name('vehicles.updateDocuments');
        Route::patch('vehicles/{id}/restore',         [VehicleController::class, 'restore'])->name('vehicles.restore');
        Route::get('vehicles/{vehicle}/export',      [VehicleController::class, 'export'])->name('vehicles.export');

        // Fuel Log routes
        Route::get('vehicles/{vehicle}/fuel', [FuelLogController::class, 'index'])->name('fuel.index');
        Route::get('vehicles/{vehicle}/fuel/create', [FuelLogController::class, 'create'])->name('fuel.create');
        Route::post('vehicles/{vehicle}/fuel', [FuelLogController::class, 'store'])->name('fuel.store');
        Route::get('vehicles/{vehicle}/fuel/{fuelLog}/edit', [FuelLogController::class, 'edit'])->name('fuel.edit');
        Route::patch('vehicles/{vehicle}/fuel/{fuelLog}', [FuelLogController::class, 'update'])->name('fuel.update');
        Route::delete('vehicles/{vehicle}/fuel/{fuelLog}', [FuelLogController::class, 'destroy'])->name('fuel.destroy');

        // Service Log routes
        Route::get('vehicles/{vehicle}/service', [ServiceLogController::class, 'index'])->name('service.index');
        Route::get('vehicles/{vehicle}/service/create', [ServiceLogController::class, 'create'])->name('service.create');
        Route::post('vehicles/{vehicle}/service', [ServiceLogController::class, 'store'])->name('service.store');
        Route::get('vehicles/{vehicle}/service/{serviceLog}/edit', [ServiceLogController::class, 'edit'])->name('service.edit');
        Route::patch('vehicles/{vehicle}/service/{serviceLog}', [ServiceLogController::class, 'update'])->name('service.update');
        Route::delete('vehicles/{vehicle}/service/{serviceLog}', [ServiceLogController::class, 'destroy'])->name('service.destroy');

        // Suggestion Engine
        Route::get('vehicles/{vehicle}/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');

        // QR Code
        Route::get('vehicles/{vehicle}/qrcode', [QrCodeController::class, 'show'])->name('qrcode.show');

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/garages/{garage}/book', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/garages/{garage}/book', [BookingController::class, 'store'])->name('bookings.store');
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    });

    // Accessible by all authenticated users
    Route::get('/parts', [PartsController::class, 'index'])->name('parts.index');
    Route::get('/garages', [GarageController::class, 'index'])->name('garages.index');
    Route::post('/garages', [GarageController::class, 'store'])->name('garages.store');

    // Invoice view — vehicle owner or garage or admin (manual authz inside controller)
    Route::get('/bookings/{booking}/invoice', [BookingController::class, 'showInvoice'])->name('bookings.invoice.show');

    // Garage routes — restricted to garage role
    Route::middleware('garage')->group(function () {
        Route::get('/garages/create', [GarageController::class, 'create'])->name('garages.create');
        Route::get('/garage/edit', [GarageController::class, 'edit'])->name('garages.edit');
        Route::patch('/garage', [GarageController::class, 'update'])->name('garages.update');
        Route::get('/garage/dashboard', [GarageController::class, 'dashboard'])->name('garage.dashboard');
        Route::get('/garage/bookings', [GarageController::class, 'bookingsList'])->name('garage.bookings');
        Route::get('/garage/invoices', [GarageController::class, 'invoices'])->name('garage.invoices');
        Route::patch('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
        Route::patch('/bookings/{booking}/note', [BookingController::class, 'storeGarageNote'])->name('bookings.note');
        Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::patch('/garage/target', [GarageController::class, 'updateTarget'])->name('garage.updateTarget');
    });

    // Admin routes — protected by admin middleware
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
        Route::get('/admin/garages', [AdminController::class, 'garages'])->name('admin.garages');
        Route::patch('/admin/garages/{garage}/toggle', [AdminController::class, 'toggleGarage'])->name('admin.garages.toggle');
        Route::get('/admin/schedules', [AdminController::class, 'schedules'])->name('admin.schedules');
        Route::post('/admin/schedules', [AdminController::class, 'storeSchedule'])->name('admin.schedules.store');
        Route::patch('/admin/schedules/{schedule}', [AdminController::class, 'updateSchedule'])->name('admin.schedules.update');
        Route::delete('/admin/schedules/{schedule}', [AdminController::class, 'destroySchedule'])->name('admin.schedules.destroy');
        Route::patch('/admin/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.status');
        Route::post('/admin/users/{user}/make-admin', [AdminController::class, 'makeAdmin'])->name('admin.makeAdmin');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

        // Parts CRUD (admin only)
        Route::get('/parts/create',      [PartsController::class, 'create'])->name('parts.create');
        Route::post('/parts',            [PartsController::class, 'store'])->name('parts.store');
        Route::get('/parts/{part}/edit', [PartsController::class, 'edit'])->name('parts.edit');
        Route::patch('/parts/{part}',    [PartsController::class, 'update'])->name('parts.update');
        Route::delete('/parts/{part}',   [PartsController::class, 'destroy'])->name('parts.destroy');
    });
});

require __DIR__.'/auth.php';