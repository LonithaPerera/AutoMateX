<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ServiceLogController extends Controller
{
    // Show all service logs for a vehicle
    public function index(Vehicle $vehicle)
    {
        $serviceLogs = $vehicle->serviceLogs()
                               ->orderBy('service_date', 'desc')
                               ->get();

        $totalCost = $serviceLogs->sum('cost');

        return view('service.index', compact('vehicle', 'serviceLogs', 'totalCost'));
    }

    // Show form to add a service log
    public function create(Vehicle $vehicle)
    {
        return view('service.create', compact('vehicle'));
    }

    // Save new service log
    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'service_type'       => 'required|string|max:150',
            'service_date'       => 'required|date',
            'mileage_at_service' => 'required|integer|min:0',
            'cost'               => 'required|numeric|min:0',
            'type'               => 'required|in:maintenance,repair,inspection',
            'garage_name'        => 'nullable|string|max:150',
            'notes'              => 'nullable|string|max:500',
        ]);

        // Update vehicle mileage if higher
        if ($request->mileage_at_service > $vehicle->mileage) {
            $vehicle->update(['mileage' => $request->mileage_at_service]);
        }

        $vehicle->serviceLogs()->create($request->all());

        return redirect()->route('service.index', $vehicle)
                         ->with('success', 'Service log added successfully!');
    }

    // Show edit form for a service log
    public function edit(Vehicle $vehicle, ServiceLog $serviceLog)
    {
        return view('service.edit', compact('vehicle', 'serviceLog'));
    }

    // Update a service log
    public function update(Request $request, Vehicle $vehicle, ServiceLog $serviceLog)
    {
        $request->validate([
            'service_type'       => 'required|string|max:150',
            'service_date'       => 'required|date',
            'mileage_at_service' => 'required|integer|min:0',
            'cost'               => 'required|numeric|min:0',
            'type'               => 'required|in:maintenance,repair,inspection',
            'garage_name'        => 'nullable|string|max:150',
            'notes'              => 'nullable|string|max:500',
        ]);

        if ($request->mileage_at_service > $vehicle->mileage) {
            $vehicle->update(['mileage' => $request->mileage_at_service]);
        }

        $serviceLog->update($request->only([
            'service_type', 'service_date', 'mileage_at_service',
            'cost', 'type', 'garage_name', 'notes',
        ]));

        return redirect()->route('service.index', $vehicle)
                         ->with('success', __('app.service_updated'));
    }

    // Delete a service log
    public function destroy(Vehicle $vehicle, ServiceLog $serviceLog)
    {
        $serviceLog->delete();
        return redirect()->route('service.index', $vehicle)
                         ->with('success', 'Service log deleted.');
    }
}