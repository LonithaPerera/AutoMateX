<?php

namespace Database\Seeders;

use App\Models\MaintenanceSchedule;
use Illuminate\Database\Seeder;

class MaintenanceScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            ['service_name' => 'Engine Oil & Filter Change',     'interval_km' => 5000,  'category' => 'maintenance', 'description' => 'Replace engine oil and oil filter to keep the engine clean and well-lubricated. Essential for engine longevity.'],
            ['service_name' => 'Air Filter Replacement',          'interval_km' => 15000, 'category' => 'maintenance', 'description' => 'A clean air filter ensures proper air-fuel mixture and protects the engine from dust and debris.'],
            ['service_name' => 'Spark Plug Replacement',          'interval_km' => 30000, 'category' => 'maintenance', 'description' => 'Worn spark plugs cause poor fuel economy and misfires. Replace to maintain engine performance.'],
            ['service_name' => 'Tyre Rotation & Wheel Alignment', 'interval_km' => 10000, 'category' => 'maintenance', 'description' => 'Rotating tyres ensures even wear and extends tyre life. Wheel alignment prevents uneven wear and improves handling.'],
            ['service_name' => 'Brake Fluid Change',              'interval_km' => 20000, 'category' => 'maintenance', 'description' => 'Brake fluid absorbs moisture over time, reducing braking effectiveness. Replace to maintain safe braking performance.'],
            ['service_name' => 'Coolant Flush',                   'interval_km' => 40000, 'category' => 'maintenance', 'description' => 'Old coolant loses its anti-corrosion properties. Flushing and replacing coolant prevents overheating and corrosion.'],
            ['service_name' => 'Transmission Fluid Change',       'interval_km' => 30000, 'category' => 'maintenance', 'description' => 'Fresh transmission fluid ensures smooth gear changes and prevents premature transmission wear.'],
            ['service_name' => 'Timing Belt Inspection',          'interval_km' => 60000, 'category' => 'inspection',  'description' => 'A worn or snapped timing belt can cause catastrophic engine damage. Inspect and replace at recommended intervals.'],
            ['service_name' => 'Battery & Electrical Check',      'interval_km' => 20000, 'category' => 'inspection',  'description' => 'Check battery charge, terminals, and electrical connections to prevent unexpected breakdowns.'],
            ['service_name' => 'Brake Pad & Disc Inspection',     'interval_km' => 15000, 'category' => 'inspection',  'description' => 'Inspect brake pads and discs for wear. Replace when pads are below 3mm to ensure safe stopping distances.'],
        ];

        foreach ($schedules as $schedule) {
            // firstOrCreate ensures re-running this seeder never duplicates rules
            MaintenanceSchedule::firstOrCreate(
                ['service_name' => $schedule['service_name']],
                $schedule
            );
        }
    }
}
