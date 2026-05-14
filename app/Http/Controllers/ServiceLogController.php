<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceLogController extends Controller
{
    // Show all service logs for a vehicle
    public function index(Vehicle $vehicle)
    {
        // Full collection for stats
        $serviceLogs = $vehicle->serviceLogs()->orderBy('service_date', 'desc')->get();
        // Paginated for list display
        $serviceLogsPaged = $vehicle->serviceLogs()->orderBy('service_date', 'desc')->paginate(10)->withQueryString();

        $totalCost = $serviceLogs->sum('cost');

        return view('service.index', compact('vehicle', 'serviceLogs', 'serviceLogsPaged', 'totalCost'));
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
            'mileage_at_service' => 'required|integer|min:' . $vehicle->mileage,
            'cost'               => 'required|numeric|min:0',
            'type'               => 'required|in:maintenance,repair,inspection',
            'garage_name'        => 'nullable|string|max:150',
            'notes'              => 'nullable|string|max:500',
        ]);

        // Update vehicle mileage if higher
        if ($request->mileage_at_service > $vehicle->mileage) {
            $vehicle->update(['mileage' => $request->mileage_at_service]);
        }

        $vehicle->serviceLogs()->create($request->only([
            'service_type', 'service_date', 'mileage_at_service',
            'cost', 'type', 'garage_name', 'notes',
        ]));

        return redirect()->route('service.index', $vehicle)
                         ->with('success', __('app.service_log_added'));
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
                         ->with('success', __('app.service_log_deleted'));
    }

    // Export service history as PDF
    public function exportPdf(Vehicle $vehicle)
    {
        // Ensure the authenticated user owns this vehicle
        if ($vehicle->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $serviceLogs = $vehicle->serviceLogs()
                               ->orderBy('service_date', 'desc')
                               ->get();

        $pdf = Pdf::loadView('service.pdf', compact('vehicle', 'serviceLogs'))
                  ->setPaper('a4', 'portrait');

        $filename = 'service-history-' . strtolower($vehicle->make) . '-' . strtolower($vehicle->model) . '-' . $vehicle->year . '.pdf';

        return $pdf->download($filename);
    }
}