<?php

namespace App\Http\Controllers;

use App\Models\FuelLog;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelLogController extends Controller
{
    private function authorise(Vehicle $vehicle): void
    {
        if ($vehicle->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    // Show all fuel logs for a vehicle
    public function index(Vehicle $vehicle)
    {
        $this->authorise($vehicle);

        $fuelLogs      = $vehicle->fuelLogs()->orderBy('date', 'desc')->get();
        $fuelLogsPaged = $vehicle->fuelLogs()->orderBy('date', 'desc')->paginate(10)->withQueryString();

        $avgKmPerLiter = $fuelLogs->avg('km_per_liter');
        $totalCost     = $fuelLogs->sum('cost');
        $totalLiters   = $fuelLogs->sum('liters');

        return view('fuel.index', compact(
            'vehicle', 'fuelLogs', 'fuelLogsPaged', 'avgKmPerLiter', 'totalCost', 'totalLiters'
        ));
    }

    // Show the form to add a fuel log
    public function create(Vehicle $vehicle)
    {
        $this->authorise($vehicle);
        return view('fuel.create', compact('vehicle'));
    }

    // Save new fuel log to database
    public function store(Request $request, Vehicle $vehicle)
    {
        $this->authorise($vehicle);

        $request->validate([
            'date'         => 'required|date',
            'liters'       => 'required|numeric|min:0.1',
            'cost'         => 'required|numeric|min:0',
            'km_reading'   => 'required|integer|min:' . $vehicle->mileage,
            'fuel_station' => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
        ]);

        $kmPerLiter = null;
        $lastLog = $vehicle->fuelLogs()
                           ->orderBy('km_reading', 'desc')
                           ->first();

        if ($lastLog && $request->km_reading > $lastLog->km_reading) {
            $kmDriven   = $request->km_reading - $lastLog->km_reading;
            $kmPerLiter = round($kmDriven / $request->liters, 2);
        }

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
                         ->with('success', __('app.fuel_log_added'));
    }

    // Show edit form for a fuel log
    public function edit(Vehicle $vehicle, FuelLog $fuelLog)
    {
        $this->authorise($vehicle);
        return view('fuel.edit', compact('vehicle', 'fuelLog'));
    }

    // Update a fuel log
    public function update(Request $request, Vehicle $vehicle, FuelLog $fuelLog)
    {
        $this->authorise($vehicle);

        $request->validate([
            'date'         => 'required|date',
            'liters'       => 'required|numeric|min:0.1',
            'cost'         => 'required|numeric|min:0',
            'km_reading'   => 'required|integer|min:0',
            'fuel_station' => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
        ]);

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
        $this->authorise($vehicle);
        $fuelLog->delete();
        return redirect()->route('fuel.index', $vehicle)
                         ->with('success', __('app.fuel_log_deleted'));
    }

    // Export fuel history as PDF
    public function exportPdf(Vehicle $vehicle)
    {
        $this->authorise($vehicle);

        $fuelLogs      = $vehicle->fuelLogs()->orderBy('date', 'desc')->get();
        $totalCost     = $fuelLogs->sum('cost');
        $totalLiters   = $fuelLogs->sum('liters');
        $avgKmPerLiter = $fuelLogs->whereNotNull('km_per_liter')->avg('km_per_liter');

        $pdf = Pdf::loadView('fuel.pdf', compact('vehicle', 'fuelLogs', 'totalCost', 'totalLiters', 'avgKmPerLiter'))
                  ->setPaper('a4', 'portrait');

        $filename = 'fuel-history-' . strtolower($vehicle->make) . '-' . strtolower($vehicle->model) . '-' . $vehicle->year . '.pdf';

        return $pdf->download($filename);
    }
}
