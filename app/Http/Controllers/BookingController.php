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

        // Ensure the vehicle belongs to the authenticated user
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        if ($vehicle->user_id !== Auth::id()) {
            abort(403, 'This vehicle does not belong to you.');
        }

        Booking::create([
            'vehicle_id'   => $request->vehicle_id,
            'garage_id'    => $garage->id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'service_type' => $request->service_type,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('bookings.index')
                         ->with('success', __('app.booking_submitted'));
    }

    // Garage owner — update booking status
    public function update(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated garage user
        $garage = Auth::user()->garage;
        if (!$garage || $booking->garage_id !== $garage->id) {
            abort(403, 'You are not authorized to update this booking.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->route('garage.dashboard')
                         ->with('success', 'Booking status updated.');
    }

    // Garage owner — save invoice details
    public function invoice(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated garage user
        $garage = Auth::user()->garage;
        if (!$garage || $booking->garage_id !== $garage->id) {
            abort(403, 'You are not authorized to invoice this booking.');
        }

        $request->validate([
            'invoice_amount' => 'nullable|numeric|min:0',
            'invoice_notes'  => 'nullable|string|max:500',
        ]);

        $booking->update([
            'invoice_amount' => $request->invoice_amount,
            'invoice_notes'  => $request->invoice_notes,
        ]);

        return redirect()->route('garage.dashboard')
                         ->with('success', 'Invoice saved successfully.');
    }

    // Vehicle owner — cancel a booking
    public function cancel(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user's vehicle
        if ($booking->vehicle->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to cancel this booking.');
        }

        // Only allow cancellation of pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('app.booking_cannot_cancel'));
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', __('app.booking_cancelled'));
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