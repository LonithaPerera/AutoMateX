<?php
namespace App\Http\Controllers;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Auth::user()->vehicles;
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'make'          => 'required|string|max:100',
            'model'         => 'required|string|max:100',
            'year'          => 'required|integer|min:1990|max:2026',
            'mileage'       => 'required|integer|min:0',
            'vin'           => 'nullable|string|max:17|unique:vehicles,vin,' . $vehicle->id,
            'license_plate' => 'nullable|string|max:20',
            'color'         => 'nullable|string|max:50',
            'fuel_type'     => 'required|in:petrol,diesel,electric,hybrid',
            'notes'         => 'nullable|string|max:500',
        ]);

        $vehicle->update($request->only([
            'make','model','year','mileage','vin','license_plate','color','fuel_type','notes',
        ]));

        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', __('app.vehicle_updated'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'make'                => 'required|string|max:100',
            'model'               => 'required|string|max:100',
            'year'                => 'required|integer|min:1990|max:2026',
            'mileage'             => 'required|integer|min:0',
            'vin'                 => 'nullable|string|max:17|unique:vehicles',
            'license_plate'       => 'nullable|string|max:20',
            'color'               => 'nullable|string|max:50',
            'fuel_type'           => 'required|in:petrol,diesel,electric,hybrid',
            'notes'               => 'nullable|string|max:500',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'insurance_expiry'    => 'nullable|date',
            'registration_expiry' => 'nullable|date',
            'emission_due'        => 'nullable|date',
        ]);

        $data = $request->only([
            'make','model','year','mileage','vin','license_plate','color','fuel_type','notes',
            'insurance_expiry','registration_expiry','emission_due',
        ]);

        if ($request->hasFile('photo')) {
            $data['image'] = $request->file('photo')->store('vehicles', 'public');
        }

        Auth::user()->vehicles()->create($data);

        return redirect()->route('vehicles.index')
                         ->with('success', __('app.vehicle_added'));
    }

    public function show(Vehicle $vehicle)
    {
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

        // Monthly spending data — last 6 months
        $monthlySpend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlySpend[] = [
                'month'   => $month->format('M y'),
                'fuel'    => (float) $vehicle->fuelLogs()
                                ->whereYear('date', $month->year)
                                ->whereMonth('date', $month->month)
                                ->sum('cost'),
                'service' => (float) $vehicle->serviceLogs()
                                ->whereYear('service_date', $month->year)
                                ->whereMonth('service_date', $month->month)
                                ->sum('cost'),
            ];
        }

        // Mileage history from fuel logs
        $mileageHistory = $vehicle->fuelLogs()
            ->orderBy('date', 'asc')
            ->orderBy('km_reading', 'asc')
            ->get(['date', 'km_reading'])
            ->map(fn($l) => ['date' => \Carbon\Carbon::parse($l->date)->format('d M y'), 'km' => (int)$l->km_reading])
            ->unique('km')
            ->values();

        // Total cost per km driven
        $firstFuelLog = $vehicle->fuelLogs()->orderBy('km_reading', 'asc')->first();
        $lastFuelLog  = $vehicle->fuelLogs()->orderBy('km_reading', 'desc')->first();
        $totalKmTracked = ($firstFuelLog && $lastFuelLog && $firstFuelLog->id !== $lastFuelLog->id)
            ? $lastFuelLog->km_reading - $firstFuelLog->km_reading
            : 0;
        $totalAllSpend = (float) $vehicle->fuelLogs()->sum('cost') + (float) $vehicle->serviceLogs()->sum('cost');
        $costPerKmStat = $totalKmTracked > 0 ? round($totalAllSpend / $totalKmTracked, 2) : null;

        // Fuel efficiency trend — last 3 fills vs previous 3 fills
        $efficiencyTrend = null;
        $trendFills = $vehicle->fuelLogs()
            ->whereNotNull('km_per_liter')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();
        if ($trendFills->count() >= 4) {
            $last3avg = $trendFills->take(3)->avg('km_per_liter');
            $prev3avg = $trendFills->slice(3)->avg('km_per_liter');
            if ($prev3avg > 0) {
                $diff = round($last3avg - $prev3avg, 2);
                $pct  = round(abs($diff) / $prev3avg * 100, 1);
                $dir  = $diff >= 0.3 ? 'up' : ($diff <= -0.3 ? 'down' : 'stable');
                $efficiencyTrend = ['direction' => $dir, 'diff' => abs($diff), 'pct' => $pct, 'value' => round($last3avg, 1)];
            }
        }

        return view('vehicles.show', compact('vehicle', 'nextService', 'monthlySpend', 'mileageHistory', 'costPerKmStat', 'efficiencyTrend'));
    }

    public function updateNotes(Request $request, Vehicle $vehicle)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);
        $vehicle->update(['notes' => $request->notes]);
        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', __('app.notes_updated'));
    }

    public function updatePhoto(Request $request, Vehicle $vehicle)
    {
        $request->validate(['photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072']);

        // Delete old photo if exists
        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }

        $path = $request->file('photo')->store('vehicles', 'public');
        $vehicle->update(['image' => $path]);

        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', __('app.photo_updated'));
    }

    public function removePhoto(Vehicle $vehicle)
    {
        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
            $vehicle->update(['image' => null]);
        }

        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', __('app.photo_removed'));
    }

    public function updateDocuments(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'insurance_expiry'    => 'nullable|date',
            'registration_expiry' => 'nullable|date',
            'emission_due'        => 'nullable|date',
        ]);

        $vehicle->update($request->only(['insurance_expiry', 'registration_expiry', 'emission_due']));

        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', __('app.documents_updated'));
    }

    public function export(Vehicle $vehicle)
    {
        $serviceLogs = $vehicle->serviceLogs()->orderBy('service_date', 'desc')->get();
        $fuelLogs    = $vehicle->fuelLogs()->orderBy('date', 'desc')->get();
        return view('vehicles.export', compact('vehicle', 'serviceLogs', 'fuelLogs'));
    }

    public function updateMileage(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'mileage' => 'required|integer|min:' . $vehicle->mileage,
        ]);
        $vehicle->update(['mileage' => $request->mileage]);
        return redirect()->route('vehicles.show', $vehicle)
                         ->with('success', __('app.mileage_updated'));
    }

    // Soft-delete (archive)
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')
                         ->with('success', __('app.vehicle_archived'));
    }

    // Show archived vehicles
    public function archived()
    {
        $vehicles = Auth::user()->vehicles()->onlyTrashed()->get();
        return view('vehicles.archived', compact('vehicles'));
    }

    // Restore archived vehicle
    public function restore($id)
    {
        $vehicle = Vehicle::withTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $vehicle->restore();
        return redirect()->route('vehicles.archived')
                         ->with('success', __('app.vehicle_restored'));
    }
}
