<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'nullable|string|max:300',
        ]);

        $booking = Booking::with('vehicle', 'rating')->findOrFail($request->booking_id);

        // Must belong to the authenticated user's vehicle
        if ($booking->vehicle->user_id !== Auth::id()) abort(403);

        // Only completed bookings can be rated
        if ($booking->status !== 'completed') abort(403);

        // Already rated
        if ($booking->rating) {
            return redirect()->route('bookings.index')->with('error', __('app.already_rated'));
        }

        Rating::create([
            'booking_id' => $booking->id,
            'user_id'    => Auth::id(),
            'garage_id'  => $booking->garage_id,
            'rating'     => $request->rating,
            'review'     => $request->review,
        ]);

        return redirect()->route('bookings.index')->with('success', __('app.rating_saved'));
    }
}
