<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

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
}