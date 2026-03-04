<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'vehicle_id', 'garage_id', 'booking_date', 'booking_time',
        'service_type', 'notes', 'status',
        'invoice_amount', 'invoice_notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
}