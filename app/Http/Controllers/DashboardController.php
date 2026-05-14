<?php

namespace App\Http\Controllers;

use App\Models\FuelLog;
use App\Models\MaintenanceSchedule;
use App\Models\ServiceLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Eager-load all vehicles with their latest service log and fuel logs
        $vehicles = $user->vehicles()
            ->with([
                'serviceLogs' => fn($q) => $q->orderBy('service_date', 'desc'),
                'fuelLogs'    => fn($q) => $q->orderBy('km_reading', 'desc'),
            ])
            ->get();

        $vehicleIds = $vehicles->pluck('id');

        // ── Aggregate stats (single queries) ────────────────────────────────
        $serviceCount = ServiceLog::whereIn('vehicle_id', $vehicleIds)->count();
        $fuelCount    = FuelLog::whereIn('vehicle_id', $vehicleIds)->count();
        $bookingCount = $user->bookings()->count();

        $monthlyFuel = (float) FuelLog::whereIn('vehicle_id', $vehicleIds)
            ->whereYear('date', now()->year)->whereMonth('date', now()->month)->sum('cost');
        $monthlySvc = (float) ServiceLog::whereIn('vehicle_id', $vehicleIds)
            ->whereYear('service_date', now()->year)->whereMonth('service_date', now()->month)->sum('cost');
        $monthlyTotal = $monthlyFuel + $monthlySvc;

        // ── Last activity ────────────────────────────────────────────────────
        $lastFuel   = FuelLog::whereIn('vehicle_id', $vehicleIds)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $lastSvcAll = ServiceLog::whereIn('vehicle_id', $vehicleIds)->orderBy('service_date', 'desc')->orderBy('id', 'desc')->first();
        $lastActivity = null;
        if ($lastFuel && $lastSvcAll) {
            $lastActivity = $lastFuel->date >= $lastSvcAll->service_date
                ? ['type' => 'fuel',    'label' => __('app.activity_fuelled'),  'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastFuel->date)->startOfDay(), false)]
                : ['type' => 'service', 'label' => __('app.activity_serviced'), 'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastSvcAll->service_date)->startOfDay(), false)];
        } elseif ($lastFuel) {
            $lastActivity = ['type' => 'fuel',    'label' => __('app.activity_fuelled'),  'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastFuel->date)->startOfDay(), false)];
        } elseif ($lastSvcAll) {
            $lastActivity = ['type' => 'service', 'label' => __('app.activity_serviced'), 'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastSvcAll->service_date)->startOfDay(), false)];
        }

        // ── Per-vehicle stats (pre-computed, no in-view queries) ─────────────
        $schedules = MaintenanceSchedule::all();

        // Bulk-load the last service per vehicle per schedule keyword to avoid N+1
        $allServiceLogs = ServiceLog::whereIn('vehicle_id', $vehicleIds)->get();

        $vehicleStats = $vehicles->map(function ($vehicle) use ($schedules, $allServiceLogs) {
            $vehicleLogs = $allServiceLogs->where('vehicle_id', $vehicle->id);

            // Last service
            $lastSvc = $vehicleLogs->sortByDesc('service_date')->first();

            // Avg efficiency
            $avgEfficiency = $vehicle->fuelLogs->whereNotNull('km_per_liter')->avg('km_per_liter');

            // Service count & total spend
            $svcCount  = $vehicleLogs->count();
            $totalSpend = $vehicleLogs->sum('cost') + $vehicle->fuelLogs->sum('cost');

            // Overdue / due soon
            $overdueCount       = 0;
            $overdueServiceName = null;
            $dueSoonServiceName = null;

            foreach ($schedules as $sched) {
                $keyword = explode(' ', $sched->service_name)[0];
                $lastMaint = $vehicleLogs
                    ->filter(fn($l) => stripos($l->service_type, $keyword) !== false)
                    ->sortByDesc('mileage_at_service')
                    ->first();
                $lastKm = $lastMaint ? $lastMaint->mileage_at_service : 0;
                $kmLeft = ($lastKm + $sched->interval_km) - $vehicle->mileage;
                if ($kmLeft <= 0) {
                    $overdueCount++;
                    if (!$overdueServiceName) $overdueServiceName = $keyword;
                } elseif ($kmLeft <= 500 && !$dueSoonServiceName) {
                    $dueSoonServiceName = $keyword;
                }
            }

            // Health score
            $mileageScore = max(0, (int) round(40 - ($vehicle->mileage / 100000) * 40));
            $svcScore     = !$lastSvc ? 10 : max(0, min(40, (int) round(40 - (\Carbon\Carbon::parse($lastSvc->service_date)->diffInDays(now()) / 365) * 40)));
            $maintScore   = max(0, 20 - ($overdueCount * 10));
            $health       = $mileageScore + $svcScore + $maintScore;

            $ringColor = $health >= 70 ? '#00f5ff' : ($health >= 40 ? '#ff6b00' : '#f87171');
            $ringGlow  = $health >= 70 ? 'rgba(0,245,255,0.3)' : ($health >= 40 ? 'rgba(255,107,0,0.3)' : 'rgba(248,113,113,0.3)');

            // Document expiry alerts
            $expiryAlerts = [];
            foreach ([
                __('app.insurance_expiry_label')    => $vehicle->insurance_expiry,
                __('app.registration_expiry_label') => $vehicle->registration_expiry,
                __('app.emission_due_label')         => $vehicle->emission_due,
            ] as $docName => $docDate) {
                if ($docDate) {
                    $dLeft = now()->startOfDay()->diffInDays($docDate->copy()->startOfDay(), false);
                    if ($dLeft < 0)       $expiryAlerts[] = ['name' => $docName, 'days' => abs((int)$dLeft), 'status' => 'expired'];
                    elseif ($dLeft <= 30) $expiryAlerts[] = ['name' => $docName, 'days' => (int)$dLeft,      'status' => 'soon'];
                }
            }

            return compact(
                'lastSvc', 'avgEfficiency', 'svcCount', 'totalSpend',
                'overdueCount', 'overdueServiceName', 'dueSoonServiceName',
                'mileageScore', 'svcScore', 'maintScore',
                'health', 'ringColor', 'ringGlow', 'expiryAlerts'
            );
        })->keyBy(fn($_, $i) => $vehicles[$i]->id);

        // ── First overdue vehicle for top alert ──────────────────────────────
        $overdueVehicle = null;
        $overdueService = null;
        foreach ($vehicles as $vehicle) {
            $stats = $vehicleStats[$vehicle->id];
            if ($stats['overdueServiceName']) {
                $overdueVehicle = $vehicle;
                $overdueService = $stats['overdueServiceName'];
                break;
            }
        }

        // ── First vehicle with an expiring/expired document ───────────────────
        $expiringDocVehicle = null;
        $expiringDocAlert   = null;
        foreach ($vehicles as $vehicle) {
            $alerts = $vehicleStats[$vehicle->id]['expiryAlerts'];
            if (!empty($alerts)) {
                $expiringDocVehicle = $vehicle;
                $expiringDocAlert   = $alerts[0];
                break;
            }
        }

        $singleVehicle = $vehicles->count() === 1 ? $vehicles->first() : null;
        $latestBooking = $user->bookings()->with(['garage', 'vehicle'])->latest()->first();

        return view('dashboard', compact(
            'vehicles', 'vehicleStats', 'singleVehicle', 'latestBooking',
            'serviceCount', 'fuelCount', 'bookingCount',
            'monthlyTotal', 'lastActivity',
            'overdueVehicle', 'overdueService',
            'expiringDocVehicle', 'expiringDocAlert'
        ));
    }
}
