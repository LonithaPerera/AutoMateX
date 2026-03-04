<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Garage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Show booking form
    public function create(Garage $garage)
    {
        $vehicles = Auth::user()->vehicles;
        return view('bookings.create', compact('garage', 'vehicles'));
    }

    // Save new booking
    public function store(Request $request, Garage $garage)
    {
        $request->validate([
            'vehicle_id'   => 'required|exists:vehicles,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required',
            'service_type' => 'required|string|max:150',
            'notes'        => 'nullable|string|max:500',
        ]);

        Booking::create([
            'vehicle_id'   => $request->vehicle_id,
            'garage_id'    => $garage->id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'service_type' => $request->service_type,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking submitted successfully!');
    }

    // Vehicle owner — see all their bookings
    public function index()
    {
        $bookings = Auth::user()
                        ->bookings()
                        ->with('garage')
                        ->orderBy('booking_date', 'desc')
                        ->get();

        return view('bookings.index', compact('bookings'));
    }
}