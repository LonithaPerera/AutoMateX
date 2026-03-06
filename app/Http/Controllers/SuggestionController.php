<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function index(Vehicle $vehicle)
    {
        $currentMileage = $vehicle->mileage;

        // --- Calculate Average Daily Mileage from Fuel Logs ---
        $avgDailyKm = null;
        $fuelLogs = $vehicle->fuelLogs()
                            ->orderBy('date', 'asc')
                            ->get();

        if ($fuelLogs->count() >= 2) {
            $firstLog  = $fuelLogs->first();
            $lastLog   = $fuelLogs->last();
            $kmDriven  = $lastLog->km_reading - $firstLog->km_reading;
            $daysDiff  = $firstLog->date->diffInDays($lastLog->date);

            if ($daysDiff > 0 && $kmDriven > 0) {
                $avgDailyKm = round($kmDriven / $daysDiff, 1);
            }
        }

        // --- Get All Maintenance Schedules (Rules) ---
        $schedules = MaintenanceSchedule::all();

        // --- Build Suggestions ---
        $suggestions = [];

        foreach ($schedules as $schedule) {
            // Find the last time this service was done on this vehicle
            $lastService = $vehicle->serviceLogs()
                ->where('service_type', 'like', '%' . $schedule->service_name . '%')
                ->orderBy('mileage_at_service', 'desc')
                ->first();

            if ($lastService) {
                $nextDueKm  = $lastService->mileage_at_service + $schedule->interval_km;
                $kmLeft     = $nextDueKm - $currentMileage;
            } else {
                // Never serviced — due based on current mileage
                $nextDueKm = $currentMileage + $schedule->interval_km;
                $kmLeft    = $schedule->interval_km;
            }

            // Calculate estimated days remaining
            $daysLeft = null;
            if ($avgDailyKm && $avgDailyKm > 0) {
                $daysLeft = round($kmLeft / $avgDailyKm);
            }

            // Determine status
if ($kmLeft <= 0) {
    $status = 'Overdue';
} elseif ($kmLeft <= 500) {
    $status = 'Due Soon';
} else {
    $status = 'Upcoming';
}

// Calculate percent progress toward next service (0–100)
$lastKm = $lastService ? $lastService->mileage_at_service : 0;
$pct = $schedule->interval_km > 0
    ? min(100, max(0, round(($currentMileage - $lastKm) / $schedule->interval_km * 100)))
    : 0;

$suggestions[] = [
    'service_name' => $schedule->service_name,
    'description'  => $schedule->description,
    'category'     => $schedule->category ?? null,
    'interval_km'  => $schedule->interval_km,
    'next_due_km'  => $nextDueKm,
    'km_remaining' => $kmLeft,       // ← renamed from km_left
    'percent'      => $pct,          // ← new
    'days_left'    => $daysLeft,
    'status'       => $status,       // ← now 'Overdue'/'Due Soon'/'Upcoming'
    'last_done_km' => $lastService ? $lastService->mileage_at_service : null,
    'last_done_date' => $lastService ? $lastService->service_date->format('d M Y') : null,
];
        }

        // Sort: overdue first, then due_soon, then ok
        usort($suggestions, function ($a, $b) {
    $order = ['Overdue' => 0, 'Due Soon' => 1, 'Upcoming' => 2];
    return $order[$a['status']] <=> $order[$b['status']];
});

        return view('suggestions.index', compact(
            'vehicle', 'suggestions', 'avgDailyKm', 'currentMileage'
        ));
    }
}