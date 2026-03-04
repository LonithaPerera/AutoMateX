<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_type',
        'service_date',
        'mileage_at_service',
        'cost',
        'garage_name',
        'notes',
        'type',
    ];

    protected $casts = [
        'service_date' => 'date',
    ];

    // A service log belongs to a vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}