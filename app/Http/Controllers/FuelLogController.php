<?php

namespace App\Http\Controllers;

use App\Models\FuelLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelLogController extends Controller
{
    // Show all fuel logs for a vehicle
    public function index(Vehicle $vehicle)
    {
        $fuelLogs = $vehicle->fuelLogs()->orderBy('date', 'desc')->get();

        // Calculate average km per liter
        $avgKmPerLiter = $fuelLogs->avg('km_per_liter');
        $totalCost     = $fuelLogs->sum('cost');
        $totalLiters   = $fuelLogs->sum('liters');

        return view('fuel.index', compact(
            'vehicle', 'fuelLogs', 'avgKmPerLiter', 'totalCost', 'totalLiters'
        ));
    }

    // Show the form to add a fuel log
    public function create(Vehicle $vehicle)
    {
        return view('fuel.create', compact('vehicle'));
    }

    // Save new fuel log to database
    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'date'         => 'required|date',
            'liters'       => 'required|numeric|min:0.1',
            'cost'         => 'required|numeric|min:0',
            'km_reading'   => 'required|integer|min:0',
            'fuel_station' => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
        ]);

        // Calculate km per liter from previous log
        $kmPerLiter = null;
        $lastLog = $vehicle->fuelLogs()
                           ->orderBy('km_reading', 'desc')
                           ->first();

        if ($lastLog && $request->km_reading > $lastLog->km_reading) {
            $kmDriven   = $request->km_reading - $lastLog->km_reading;
            $kmPerLiter = round($kmDriven / $request->liters, 2);
        }

        // Update vehicle mileage
        if ($request->km_reading > $vehicle->mileage) {
            $vehicle->update(['mileage' => $request->km_reading]);
        }

        $vehicle->fuelLogs()->create([
            'date'         => $request->date,
            'liters'       => $request->liters,
            'cost'         => $request->cost,
            'km_reading'   => $request->km_reading,
            'km_per_liter' => $kmPerLiter,
            'fuel_station' => $request->fuel_station,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('fuel.index', $vehicle)
                         ->with('success', 'Fuel log added successfully!');
    }

    // Show edit form for a fuel log
    public function edit(Vehicle $vehicle, FuelLog $fuelLog)
    {
        return view('fuel.edit', compact('vehicle', 'fuelLog'));
    }

    // Update a fuel log
    public function update(Request $request, Vehicle $vehicle, FuelLog $fuelLog)
    {
        $request->validate([
            'date'         => 'required|date',
            'liters'       => 'required|numeric|min:0.1',
            'cost'         => 'required|numeric|min:0',
            'km_reading'   => 'required|integer|min:0',
            'fuel_station' => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
        ]);

        // Recalculate km/L from the previous log (excluding this one)
        $kmPerLiter = $fuelLog->km_per_liter;
        $prevLog = $vehicle->fuelLogs()
                           ->where('id', '!=', $fuelLog->id)
                           ->where('km_reading', '<', $request->km_reading)
                           ->orderBy('km_reading', 'desc')
                           ->first();

        if ($prevLog) {
            $kmDriven   = $request->km_reading - $prevLog->km_reading;
            $kmPerLiter = $kmDriven > 0 ? round($kmDriven / $request->liters, 2) : null;
        }

        $fuelLog->update([
            'date'         => $request->date,
            'liters'       => $request->liters,
            'cost'         => $request->cost,
            'km_reading'   => $request->km_reading,
            'km_per_liter' => $kmPerLiter,
            'fuel_station' => $request->fuel_station,
            'notes'        => $request->notes,
        ]);

        if ($request->km_reading > $vehicle->mileage) {
            $vehicle->update(['mileage' => $request->km_reading]);
        }

        return redirect()->route('fuel.index', $vehicle)
                         ->with('success', __('app.fuel_updated'));
    }

    // Delete a fuel log
    public function destroy(Vehicle $vehicle, FuelLog $fuelLog)
    {
        $fuelLog->delete();
        return redirect()->route('fuel.index', $vehicle)
                         ->with('success', 'Fuel log deleted.');
    }
}