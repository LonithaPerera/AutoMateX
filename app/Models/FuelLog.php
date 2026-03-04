<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'liters',
        'cost',
        'km_reading',
        'km_per_liter',
        'fuel_station',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // A fuel log belongs to a vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}