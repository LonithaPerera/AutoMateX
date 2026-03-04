<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'service_name' => 'Engine Oil & Filter Change',
                'interval_km'  => 5000,
                'category'     => 'maintenance',
                'description'  => 'Replace engine oil and oil filter to keep engine healthy.',
            ],
            [
                'service_name' => 'Air Filter Replacement',
                'interval_km'  => 15000,
                'category'     => 'maintenance',
                'description'  => 'Replace air filter for better engine performance and fuel economy.',
            ],
            [
                'service_name' => 'Tire Rotation & Balance',
                'interval_km'  => 10000,
                'category'     => 'maintenance',
                'description'  => 'Rotate and balance tires for even wear and smooth driving.',
            ],
            [
                'service_name' => 'Brake Pad Inspection',
                'interval_km'  => 20000,
                'category'     => 'inspection',
                'description'  => 'Inspect brake pads for wear and replace if necessary.',
            ],
            [
                'service_name' => 'Spark Plug Replacement',
                'interval_km'  => 30000,
                'category'     => 'maintenance',
                'description'  => 'Replace spark plugs for optimal engine combustion.',
            ],
            [
                'service_name' => 'Transmission Fluid Change',
                'interval_km'  => 40000,
                'category'     => 'maintenance',
                'description'  => 'Change transmission fluid to protect gearbox.',
            ],
            [
                'service_name' => 'Coolant Flush',
                'interval_km'  => 40000,
                'category'     => 'maintenance',
                'description'  => 'Flush and replace engine coolant to prevent overheating.',
            ],
            [
                'service_name' => 'Timing Belt Replacement',
                'interval_km'  => 80000,
                'category'     => 'maintenance',
                'description'  => 'Replace timing belt to prevent engine damage.',
            ],
        ];

        DB::table('maintenance_schedules')->insert($schedules);
    }
}