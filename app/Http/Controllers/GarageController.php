<?php

namespace App\Http\Controllers;

use App\Models\Garage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarageController extends Controller
{
    // List all garages (for vehicle owners to browse)
    public function index()
    {
        $garages = Garage::where('is_active', true)->get();
        return view('garages.index', compact('garages'));
    }

    // Show garage profile setup form
    public function create()
    {
        return view('garages.create');
    }

    // Save new garage profile
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:150',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'description'    => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:150',
        ]);

        Garage::create([
            'user_id'        => Auth::id(),
            'name'           => $request->name,
            'address'        => $request->address,
            'city'           => $request->city,
            'phone'          => $request->phone,
            'description'    => $request->description,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.garage_created'));
    }

    // Show garage profile edit form
    public function edit()
    {
        $garage = Auth::user()->garage;
        if (!$garage) {
            return redirect()->route('garages.create')
                             ->with('info', 'Please set up your garage profile first.');
        }
        return view('garages.edit', compact('garage'));
    }

    // Save updated garage profile
    public function update(Request $request)
    {
        $garage = Auth::user()->garage;
        if (!$garage) abort(403);

        $request->validate([
            'name'           => 'required|string|max:150',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'description'    => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:150',
        ]);

        $garage->update([
            'name'           => $request->name,
            'address'        => $request->address,
            'city'           => $request->city,
            'phone'          => $request->phone,
            'description'    => $request->description,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.garage_updated'));
    }

    // Garage owner dashboard — see all bookings
    public function dashboard()
    {
        $garage = Auth::user()->garage;

        if (!$garage) {
            return redirect()->route('garages.create')
                             ->with('info', 'Please set up your garage profile first.');
        }

        $bookings = $garage->bookings()
                           ->with('vehicle.user')
                           ->orderBy('booking_date', 'asc')
                           ->get();

        return view('garages.dashboard', compact('garage', 'bookings'));
    }
}