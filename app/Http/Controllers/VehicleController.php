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
        return view('vehicles.show', compact('vehicle'));
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