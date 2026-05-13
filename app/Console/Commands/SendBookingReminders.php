<?php

namespace App\Console\Commands;

use App\Models\AppNotification;
use App\Models\Booking;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature   = 'bookings:remind';
    protected $description = 'Send in-app reminder notifications for bookings scheduled tomorrow';

    public function handle(): void
    {
        $tomorrow = now()->addDay()->toDateString();

        $bookings = Booking::with('vehicle.user', 'garage')
            ->whereDate('booking_date', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $sent = 0;

        foreach ($bookings as $booking) {
            $user = optional($booking->vehicle)->user;
            if (!$user) continue;

            // Avoid duplicate reminders (check if one already exists today)
            $alreadySent = AppNotification::where('user_id', $user->id)
                ->where('type', 'booking_reminder')
                ->where('url', route('bookings.show', $booking))
                ->whereDate('created_at', today())
                ->exists();

            if ($alreadySent) continue;

            AppNotification::create([
                'user_id' => $user->id,
                'type'    => 'booking_reminder',
                'title'   => __('app.booking_reminder_title'),
                'message' => __('app.booking_reminder_message', [
                    'service' => $booking->service_type,
                    'garage'  => $booking->garage->name ?? 'the garage',
                    'time'    => \Carbon\Carbon::parse($booking->booking_time)->format('h:i A'),
                ]),
                'url'     => route('bookings.show', $booking),
            ]);

            $sent++;
        }

        $this->info("Sent {$sent} booking reminder(s).");
    }
}
