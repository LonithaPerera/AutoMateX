<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
        'service_name',
        'interval_km',
        'category',
        'description',
    ];
}