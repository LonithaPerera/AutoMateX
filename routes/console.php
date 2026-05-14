<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send booking reminder notifications once daily at 8 AM
Schedule::command('bookings:remind')->dailyAt('08:00');

// Check vehicle document expiry and notify owners once daily at 9 AM
Schedule::command('vehicles:check-expiry')->dailyAt('09:00');
