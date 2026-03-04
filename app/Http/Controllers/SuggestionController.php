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
                $status = 'overdue';
            } elseif ($kmLeft <= 500) {
                $status = 'due_soon';
            } else {
                $status = 'ok';
            }

            $suggestions[] = [
                'service_name' => $schedule->service_name,
                'description'  => $schedule->description,
                'interval_km'  => $schedule->interval_km,
                'next_due_km'  => $nextDueKm,
                'km_left'      => $kmLeft,
                'days_left'    => $daysLeft,
                'status'       => $status,
                'last_done_km' => $lastService ? $lastService->mileage_at_service : null,
                'last_done_date' => $lastService ? $lastService->service_date->format('d M Y') : null,
            ];
        }

        // Sort: overdue first, then due_soon, then ok
        usort($suggestions, function ($a, $b) {
            $order = ['overdue' => 0, 'due_soon' => 1, 'ok' => 2];
            return $order[$a['status']] <=> $order[$b['status']];
        });

        return view('suggestions.index', compact(
            'vehicle', 'suggestions', 'avgDailyKm', 'currentMileage'
        ));
    }
}