<?php

namespace App\Http\Controllers;

use App\Models\TripLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TripLogController extends Controller
{
    public function index(Vehicle $vehicle)
    {
        $allTrips    = $vehicle->tripLogs()->orderBy('trip_date', 'desc')->get();
        $tripLogs    = $vehicle->tripLogs()->orderBy('trip_date', 'desc')->paginate(10)->withQueryString();
        $totalKm     = $allTrips->sum(fn($t) => $t->distance);
        $businessKm  = $allTrips->where('purpose', 'business')->sum(fn($t) => $t->distance);
        $personalKm  = $allTrips->where('purpose', 'personal')->sum(fn($t) => $t->distance);
        $tripCount   = $allTrips->count();

        return view('trips.index', compact(
            'vehicle', 'tripLogs', 'totalKm', 'businessKm', 'personalKm', 'tripCount'
        ));
    }

    public function create(Vehicle $vehicle)
    {
        return view('trips.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'trip_date' => 'required|date',
            'start_km'  => 'required|integer|min:0',
            'end_km'    => 'required|integer|min:0|gte:start_km',
            'purpose'   => 'required|in:personal,business,other',
            'notes'     => 'nullable|string|max:300',
        ]);

        $vehicle->tripLogs()->create($request->only([
            'trip_date', 'start_km', 'end_km', 'purpose', 'notes',
        ]));

        return redirect()->route('trips.index', $vehicle)
                         ->with('success', __('app.trip_added'));
    }

    public function edit(Vehicle $vehicle, TripLog $tripLog)
    {
        return view('trips.edit', compact('vehicle', 'tripLog'));
    }

    public function update(Request $request, Vehicle $vehicle, TripLog $tripLog)
    {
        $request->validate([
            'trip_date' => 'required|date',
            'start_km'  => 'required|integer|min:0',
            'end_km'    => 'required|integer|min:0|gte:start_km',
            'purpose'   => 'required|in:personal,business,other',
            'notes'     => 'nullable|string|max:300',
        ]);

        $tripLog->update($request->only([
            'trip_date', 'start_km', 'end_km', 'purpose', 'notes',
        ]));

        return redirect()->route('trips.index', $vehicle)
                         ->with('success', __('app.trip_updated'));
    }

    public function destroy(Vehicle $vehicle, TripLog $tripLog)
    {
        $tripLog->delete();
        return redirect()->route('trips.index', $vehicle)
                         ->with('success', __('app.trip_deleted'));
    }
}
