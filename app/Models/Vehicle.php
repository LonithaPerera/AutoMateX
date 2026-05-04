<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;
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
        'notes',
        'image',
        'qr_token',
        'insurance_expiry',
        'registration_expiry',
        'emission_due',
    ];

    protected $casts = [
        'insurance_expiry'    => 'date',
        'registration_expiry' => 'date',
        'emission_due'        => 'date',
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