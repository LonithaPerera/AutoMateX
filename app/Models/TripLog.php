<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripLog extends Model
{
    protected $fillable = [
        'vehicle_id', 'trip_date', 'start_km', 'end_km', 'purpose', 'notes',
    ];

    protected $casts = [
        'trip_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Computed distance
    public function getDistanceAttribute(): int
    {
        return max(0, $this->end_km - $this->start_km);
    }
}
