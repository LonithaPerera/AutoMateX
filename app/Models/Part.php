<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'vehicle_make',
        'vehicle_model',
        'vehicle_year_from',
        'vehicle_year_to',
        'part_name',
        'part_category',
        'oem_part_number',
        'alternative_part_number',
        'brand',
        'description',
    ];
}