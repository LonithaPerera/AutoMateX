<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'make',
        'model',
        'year',
        'mileage',
        'vin',
        'license_plate',
        'color',
        'fuel_type',
        'image',
        'qr_token',
    ];

    // Auto-generate QR token when vehicle is created
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vehicle) {
            $vehicle->qr_token = Str::uuid();
        });
    }

    // A vehicle belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A vehicle can have many fuel logs
    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }

    // A vehicle can have many service logs
    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }
}