<?php

namespace App\Http\Controllers;

use App\Mail\BookingCancelledNotification;
use App\Mail\BookingReceivedNotification;
use App\Mail\BookingStatusUpdated;
use App\Mail\NewBookingNotification;
use App\Models\AppNotification;
use App\Models\Booking;
use App\Models\Garage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        // #1 — Working hours validation
        if ($garage->working_hours) {
            $dayKeys  = ['sun','mon','tue','wed','thu','fri','sat'];
            $dayKey   = $dayKeys[\Carbon\Carbon::parse($request->booking_date)->dayOfWeek];
            $dayHours = $garage->working_hours[$dayKey] ?? null;
            if ($dayHours) {
                if ($dayHours['closed'] ?? false) {
                    return back()->withInput()->with('error', __('app.garage_closed_that_day'));
                }
                $open  = \Carbon\Carbon::parse($request->booking_date . ' ' . ($dayHours['open']  ?? '00:00'));
                $close = \Carbon\Carbon::parse($request->booking_date . ' ' . ($dayHours['close'] ?? '23:59'));
                $time  = \Carbon\Carbon::parse($request->booking_date . ' ' . $request->booking_time);
                if ($time->lt($open) || $time->gt($close)) {
                    return back()->withInput()->with('error', __('app.booking_outside_hours'));
                }
            }
        }

        // #2 — Duplicate slot prevention
        $duplicate = Booking::where('garage_id', $garage->id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->whereNotIn('status', ['cancelled'])
            ->exists();
        if ($duplicate) {
            return back()->withInput()->with('error', __('app.booking_slot_taken'));
        }

        $booking = Booking::create([
            'vehicle_id'   => $request->vehicle_id,
            'garage_id'    => $garage->id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'service_type' => $request->service_type,
            'notes'        => $request->notes,
        ]);

        $booking->load('vehicle.user', 'garage.user');

        // In-app notification to garage owner
        if ($garage->user) {
            AppNotification::create([
                'user_id' => $garage->user->id,
                'type'    => 'booking_new',
                'title'   => 'New Booking Request',
                'message' => $booking->vehicle->user->name . ' booked ' . $booking->service_type,
                'url'     => route('garage.bookings'),
            ]);
        }

        // Emails
        try {
            Mail::to($booking->vehicle->user->email)
                ->send(new BookingReceivedNotification($booking));
            if ($garage->user && $garage->user->email) {
                Mail::to($garage->user->email)
                    ->send(new NewBookingNotification($booking));
            }
        } catch (\Exception $e) {}

        // #9 — Redirect to confirmation view
        return redirect()->route('bookings.show', $booking)
                         ->with('just_booked', true);
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
        $booking->load('vehicle.user', 'garage');

        // In-app notification to vehicle owner
        $notifMessages = [
            'confirmed' => ['title' => 'Booking Confirmed', 'msg' => $booking->garage->name . ' confirmed your ' . $booking->service_type],
            'completed' => ['title' => 'Service Completed', 'msg' => $booking->garage->name . ' marked your ' . $booking->service_type . ' as completed'],
            'cancelled' => ['title' => 'Booking Cancelled', 'msg' => $booking->garage->name . ' cancelled your ' . $booking->service_type],
        ];
        if (isset($notifMessages[$request->status])) {
            AppNotification::create([
                'user_id' => $booking->vehicle->user->id,
                'type'    => 'booking_' . $request->status,
                'title'   => $notifMessages[$request->status]['title'],
                'message' => $notifMessages[$request->status]['msg'],
                'url'     => route('bookings.show', $booking),
            ]);
        }

        // Email on confirmed or completed
        if (in_array($request->status, ['confirmed', 'completed'])) {
            try {
                Mail::to($booking->vehicle->user->email)
                    ->send(new BookingStatusUpdated($booking));
            } catch (\Exception $e) {}
        }

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.admin_booking_status_updated'));
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

        // Auto-generate invoice number if not already set
        if (!$booking->invoice_number && $request->invoice_amount) {
            $year  = now()->year;
            $count = Booking::whereNotNull('invoice_number')->count() + 1;
            $invoiceNumber = 'INV-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        } else {
            $invoiceNumber = $booking->invoice_number;
        }

        // Set invoice date on first save
        $invoiceDate = $booking->invoice_date ?? ($request->invoice_amount ? now()->toDateString() : null);

        $booking->update([
            'invoice_amount' => $request->invoice_amount,
            'invoice_notes'  => $request->invoice_notes,
            'invoice_number' => $invoiceNumber,
            'invoice_date'   => $invoiceDate,
        ]);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.invoice_saved'));
    }

    // View printable invoice — vehicle owner, garage, or admin
    public function showInvoice(Booking $booking)
    {
        $user = Auth::user();

        $isOwner  = $booking->vehicle->user_id === $user->id;
        $isGarage = $user->garage && $booking->garage_id === $user->garage->id;
        $isAdmin  = $user->role === 'admin';

        if (!$isOwner && !$isGarage && !$isAdmin) {
            abort(403, 'You are not authorized to view this invoice.');
        }

        if (!$booking->invoice_amount) {
            return back()->with('error', __('app.no_invoice_available'));
        }

        $booking->load('vehicle.user', 'garage');
        return view('bookings.invoice', compact('booking'));
    }

    // Garage owner — save a note/reply visible to the customer
    public function storeGarageNote(Request $request, Booking $booking)
    {
        $garage = Auth::user()->garage;
        if (!$garage || $booking->garage_id !== $garage->id) {
            abort(403, 'You are not authorized to add notes to this booking.');
        }

        $request->validate([
            'garage_notes' => 'nullable|string|max:500',
        ]);

        $booking->update(['garage_notes' => $request->garage_notes]);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.note_saved'));
    }

    // Vehicle owner — cancel a booking
    public function cancel(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user's vehicle
        if ($booking->vehicle->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to cancel this booking.');
        }

        // Only allow cancellation of pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('app.booking_cannot_cancel'));
        }

        $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status'        => 'cancelled',
            'cancel_reason' => $request->cancel_reason,
        ]);

        $booking->load('vehicle.user', 'garage.user');

        // In-app notification to garage owner
        if ($booking->garage->user) {
            AppNotification::create([
                'user_id' => $booking->garage->user->id,
                'type'    => 'booking_cancelled',
                'title'   => 'Booking Cancelled',
                'message' => $booking->vehicle->user->name . ' cancelled their ' . $booking->service_type . ' booking',
                'url'     => route('garage.bookings'),
            ]);
        }

        try {
            if ($booking->garage->user && $booking->garage->user->email) {
                Mail::to($booking->garage->user->email)
                    ->send(new BookingCancelledNotification($booking));
            }
        } catch (\Exception $e) {}

        return back()->with('success', __('app.booking_cancelled'));
    }

    // Vehicle owner — reschedule a pending/confirmed booking
    public function reschedule(Request $request, Booking $booking)
    {
        if ($booking->vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('app.booking_cannot_reschedule'));
        }

        $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required',
        ]);

        $booking->update([
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'status'       => 'pending',
        ]);

        $booking->load('vehicle.user', 'garage.user');

        // Notify garage owner
        if ($booking->garage->user) {
            AppNotification::create([
                'user_id' => $booking->garage->user->id,
                'type'    => 'booking_rescheduled',
                'title'   => 'Booking Rescheduled',
                'message' => $booking->vehicle->user->name . ' rescheduled their ' . $booking->service_type . ' to ' . \Carbon\Carbon::parse($request->booking_date)->format('d M Y'),
                'url'     => route('garage.bookings'),
            ]);
        }

        return back()->with('success', __('app.booking_rescheduled'));
    }

    // Vehicle owner — see detail for one booking
    public function show(Booking $booking)
    {
        $user = Auth::user();

        // Must belong to the authenticated user's vehicle
        if ($booking->vehicle->user_id !== $user->id) {
            abort(403, 'You are not authorized to view this booking.');
        }

        $booking->load('garage', 'vehicle', 'rating');
        return view('bookings.show', compact('booking'));
    }

    // Vehicle owner — see all their bookings
    public function index()
    {
        $bookings = Auth::user()
                        ->bookings()
                        ->with('garage', 'vehicle', 'rating')
                        ->orderBy('booking_date', 'desc')
                        ->get();

        return view('bookings.index', compact('bookings'));
    }
}