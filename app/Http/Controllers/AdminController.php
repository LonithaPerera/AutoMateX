<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FuelLog;
use App\Models\Garage;
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

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBookings'));
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
                         ->with('success', $user->name . ' is now an admin.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                             ->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users')
                         ->with('success', 'User deleted successfully.');
    }
}