<?php

namespace App\Providers;

use App\Models\AppNotification;
use App\Observers\AppNotificationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        AppNotification::observe(AppNotificationObserver::class);
    }
}
