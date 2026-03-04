<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\FuelLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicle routes
    Route::resource('vehicles', VehicleController::class);

    // Fuel Log routes (nested under vehicles)
    Route::get('vehicles/{vehicle}/fuel', [FuelLogController::class, 'index'])
         ->name('fuel.index');
    Route::get('vehicles/{vehicle}/fuel/create', [FuelLogController::class, 'create'])
         ->name('fuel.create');
    Route::post('vehicles/{vehicle}/fuel', [FuelLogController::class, 'store'])
         ->name('fuel.store');
    Route::delete('vehicles/{vehicle}/fuel/{fuelLog}', [FuelLogController::class, 'destroy'])
         ->name('fuel.destroy');
});

require __DIR__.'/auth.php';