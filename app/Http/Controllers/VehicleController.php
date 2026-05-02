<?php
namespace App\Http\Controllers;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class VehicleController extends Controller
{
    // Show all vehicles for logged-in user
    public function index()
    {
        $vehicles = Auth::user()->vehicles;
        return view('vehicles.index', compact('vehicles'));
    }
    // Show the form to add a new vehicle
    public function create()
    {
        return view('vehicles.create');
    }
    // Save new vehicle to database
    public function store(Request $request)
    {
        $request->validate([
            'make'          => 'required|string|max:100',
            'model'         => 'required|string|max:100',
            'year'          => 'required|integer|min:1990|max:2026',
            'mileage'       => 'required|integer|min:0',
            'vin'           => 'nullable|string|max:17|unique:vehicles',
            'license_plate' => 'nullable|string|max:20',
            'color'         => 'nullable|string|max:50',
            'fuel_type'     => 'required|in:petrol,diesel,electric,hybrid',
        ]);
        Auth::user()->vehicles()->create($request->all());
        return redirect()->route('vehicles.index')
                         ->with('success', 'Vehicle added successfully!');
    }
    // Show a single vehicle
    public function show(Vehicle $vehicle)
    {
        // Find the next due or most overdue service
        $schedules   = \App\Models\MaintenanceSchedule::all();
        $nextService = null;
        $minKmLeft   = PHP_INT_MAX;

        foreach ($schedules as $schedule) {
            $lastService = $vehicle->serviceLogs()
                ->where('service_type', 'like', '%' . $schedule->service_name . '%')
                ->orderBy('mileage_at_service', 'desc')
                ->first();

            $lastKm  = $lastService ? $lastService->mileage_at_service : 0;
            $nextDue = $lastKm + $schedule->interval_km;
            $kmLeft  = $nextDue - $vehicle->mileage;

            if ($kmLeft < $minKmLeft) {
                $minKmLeft   = $kmLeft;
                $nextService = [
                    'name'    => $schedule->service_name,
                    'km_left' => $kmLeft,
                    'status'  => $kmLeft <= 0 ? 'overdue' : ($kmLeft <= 500 ? 'due_soon' : 'upcoming'),
                ];
            }
        }

        return view('vehicles.show', compact('vehicle', 'nextService'));
    }
    // Update vehicle mileage manually
    public function updateMileage(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'mileage' => 'required|integer|min:' . $vehicle->mileage,
        ]);
        $vehicle->update(['mileage' => $request->mileage]);
        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', 'Mileage updated successfully!');
    }
    // Delete a vehicle
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')
                         ->with('success', 'Vehicle removed successfully!');
    }
}