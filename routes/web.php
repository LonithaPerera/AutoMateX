<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\ServiceLogController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PartsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Public QR route — NO login required
Route::get('/vehicle/public/{token}', [QrCodeController::class, 'publicView'])
     ->name('public.vehicle');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicle routes
    Route::resource('vehicles', VehicleController::class);

    // Fuel Log routes
    Route::get('vehicles/{vehicle}/fuel', [FuelLogController::class, 'index'])
         ->name('fuel.index');
    Route::get('vehicles/{vehicle}/fuel/create', [FuelLogController::class, 'create'])
         ->name('fuel.create');
    Route::post('vehicles/{vehicle}/fuel', [FuelLogController::class, 'store'])
         ->name('fuel.store');
    Route::delete('vehicles/{vehicle}/fuel/{fuelLog}', [FuelLogController::class, 'destroy'])
         ->name('fuel.destroy');

    // Service Log routes
    Route::get('vehicles/{vehicle}/service', [ServiceLogController::class, 'index'])
         ->name('service.index');
    Route::get('vehicles/{vehicle}/service/create', [ServiceLogController::class, 'create'])
         ->name('service.create');
    Route::post('vehicles/{vehicle}/service', [ServiceLogController::class, 'store'])
         ->name('service.store');
    Route::delete('vehicles/{vehicle}/service/{serviceLog}', [ServiceLogController::class, 'destroy'])
         ->name('service.destroy');

    // Suggestion Engine
    Route::get('vehicles/{vehicle}/suggestions', [SuggestionController::class, 'index'])
         ->name('suggestions.index');

    // Parts Verification
    Route::get('/parts', [PartsController::class, 'index'])->name('parts.index');

    // QR Code
    Route::get('vehicles/{vehicle}/qrcode', [QrCodeController::class, 'show'])
         ->name('qrcode.show');
});

require __DIR__.'/auth.php';