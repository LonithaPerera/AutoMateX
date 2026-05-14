<?php

namespace App\Console\Commands;

use App\Models\AppNotification;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckDocumentExpiry extends Command
{
    protected $signature   = 'vehicles:check-expiry';
    protected $description = 'Send notifications for vehicle documents expiring within 30 days';

    public function handle(): void
    {
        $vehicles = Vehicle::with('user')
            ->whereNotNull('user_id')
            ->get();

        $docs = [
            'insurance_expiry'    => 'Insurance',
            'registration_expiry' => 'Registration',
            'emission_due'        => 'Emission Test',
        ];

        $sent = 0;

        foreach ($vehicles as $vehicle) {
            if (!$vehicle->user) continue;

            foreach ($docs as $field => $label) {
                $date = $vehicle->$field;
                if (!$date) continue;

                $daysLeft = (int) now()->startOfDay()->diffInDays(
                    Carbon::parse($date)->startOfDay(), false
                );

                // Notify at 30, 14, 7, 1 days before, and on expiry day (0)
                if (!in_array($daysLeft, [30, 14, 7, 1, 0])) continue;

                // Avoid duplicate — only one per vehicle/doc/day
                $alreadySent = AppNotification::where('user_id', $vehicle->user->id)
                    ->where('type', 'doc_expiry')
                    ->where('url', route('vehicles.show', $vehicle))
                    ->whereDate('created_at', today())
                    ->where('message', 'like', '%' . $label . '%')
                    ->exists();

                if ($alreadySent) continue;

                if ($daysLeft <= 0) {
                    $title   = __('app.doc_expiry_notif_title_expired');
                    $message = __('app.doc_expiry_notif_msg_expired', [
                        'doc'     => $label,
                        'vehicle' => $vehicle->make . ' ' . $vehicle->model,
                    ]);
                } else {
                    $title   = __('app.doc_expiry_notif_title_soon');
                    $message = __('app.doc_expiry_notif_msg_soon', [
                        'doc'     => $label,
                        'vehicle' => $vehicle->make . ' ' . $vehicle->model,
                        'days'    => $daysLeft,
                    ]);
                }

                AppNotification::create([
                    'user_id' => $vehicle->user->id,
                    'type'    => 'doc_expiry',
                    'title'   => $title,
                    'message' => $message,
                    'url'     => route('vehicles.show', $vehicle),
                ]);

                $sent++;
            }
        }

        $this->info("Sent {$sent} document expiry notification(s).");
    }
}
