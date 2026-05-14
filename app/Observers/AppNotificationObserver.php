<?php

namespace App\Observers;

use App\Models\AppNotification;
use App\Models\User;
use App\Services\PushNotificationService;

class AppNotificationObserver
{
    public function created(AppNotification $notification): void
    {
        try {
            $user = User::find($notification->user_id);
            if (!$user) return;

            $service = new PushNotificationService();
            $service->sendToUser(
                $user,
                $notification->title,
                $notification->message,
                $notification->url ?? '/notifications'
            );
        } catch (\Throwable $e) {
            // Push failure must never break the main flow
        }
    }
}
