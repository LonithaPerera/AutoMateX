<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FuelLog;
use App\Models\Garage;
use App\Models\MaintenanceSchedule;
use App\Models\ServiceLog;
use App\Models\User;
use App\Models\Vehicle;

class AdminController extends Controller
{
    public function dashboard()
    {
        // System-wide stats
        $stats = [
            'total_users'        => User::count(),
            'total_vehicles'     => Vehicle::count(),
            'total_service_logs' => ServiceLog::count(),
            'total_fuel_logs'    => FuelLog::count(),
            'total_bookings'     => Booking::count(),
            'total_garages'      => Garage::count(),
            'pending_bookings'   => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
        ];

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        // Recent bookings
        $recentBookings = Booking::with(['vehicle.user', 'garage'])
                                 ->orderBy('created_at', 'desc')
                                 ->take(10)
                                 ->get();

        // Chart 1 — Bookings by status
        $bookingsByStatus = [
            'pending'   => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Chart 2 — Monthly bookings (last 6 months)
        $monthlyRaw = Booking::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month);

        $monthlyBookings = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key  = $date->year . '-' . $date->month;
            $monthlyBookings[] = [
                'label' => $date->format('M Y'),
                'count' => (int) ($monthlyRaw->get($key)?->count ?? 0),
            ];
        }

        // Chart 3 — Top 5 garages by bookings
        $topGarages = Garage::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get(['name', 'bookings_count']);

        return view('admin.dashboard', compact(
            'stats', 'recentUsers', 'recentBookings',
            'bookingsByStatus', 'monthlyBookings', 'topGarages'
        ));
    }

    public function users()
    {
        $users = User::withCount(['vehicles'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.users', compact('users'));
    }

    public function makeAdmin(User $user)
    {
        $user->update(['role' => 'admin']);
        return redirect()->route('admin.users')
                         ->with('success', __('app.admin_make_admin_success', ['name' => $user->name]));
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                             ->with('error', __('app.admin_cannot_delete_self'));
        }
        $user->delete();
        return redirect()->route('admin.users')
                         ->with('success', __('app.admin_user_deleted'));
    }

    public function garages()
    {
        $garages = Garage::with('user')->withCount('bookings')->orderBy('created_at', 'desc')->get();
        return view('admin.garages', compact('garages'));
    }

    public function toggleGarage(Garage $garage)
    {
        $garage->update(['is_active' => !$garage->is_active]);
        return back()->with('success', __('app.admin_garage_toggled', ['name' => $garage->name]));
    }

    public function schedules()
    {
        $schedules = MaintenanceSchedule::orderBy('category')->orderBy('service_name')->get();
        return view('admin.schedules', compact('schedules'));
    }

    public function storeSchedule(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'interval_km'  => 'required|integer|min:100',
            'category'     => 'required|in:maintenance,repair,inspection',
            'description'  => 'nullable|string|max:500',
        ]);
        MaintenanceSchedule::create($request->only('service_name','interval_km','category','description'));
        return back()->with('success', __('app.admin_schedule_added'));
    }

    public function updateSchedule(\Illuminate\Http\Request $request, MaintenanceSchedule $schedule)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'interval_km'  => 'required|integer|min:100',
            'category'     => 'required|in:maintenance,repair,inspection',
            'description'  => 'nullable|string|max:500',
        ]);
        $schedule->update($request->only('service_name','interval_km','category','description'));
        return back()->with('success', __('app.admin_schedule_updated'));
    }

    public function destroySchedule(MaintenanceSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', __('app.admin_schedule_deleted'));
    }

    public function updateBookingStatus(\Illuminate\Http\Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);
        $booking->update(['status' => $request->status]);
        return back()->with('success', __('app.admin_booking_status_updated'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['vehicle.user', 'garage'])
                           ->orderBy('created_at', 'desc')
                           ->get();

        $stats = [
            'total'     => $bookings->count(),
            'pending'   => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings', compact('bookings', 'stats'));
    }
}