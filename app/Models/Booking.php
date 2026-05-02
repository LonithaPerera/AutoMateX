<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'vehicle_id', 'garage_id', 'booking_date', 'booking_time',
        'service_type', 'notes', 'garage_notes', 'status',
        'invoice_amount', 'invoice_notes', 'invoice_number', 'invoice_date',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'invoice_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
