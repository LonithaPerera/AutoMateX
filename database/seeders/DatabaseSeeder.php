<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Garage;
use App\Models\Booking;
use App\Models\FuelLog;
use App\Models\ServiceLog;
use App\Models\MaintenanceSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── MAINTENANCE SCHEDULE RULES ───────────────────────────────────────
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
            MaintenanceSchedule::create($schedule);
        }

        // ─── ADMIN ────────────────────────────────────────────────────────────
        User::create([
            'name'              => 'Ashan Perera',
            'email'             => 'admin@automatex.lk',
            'password'          => Hash::make('Admin@1234'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // ─── GARAGE 1 — Speedy Motors ─────────────────────────────────────────
        $speedyUser = User::create([
            'name'              => 'Speedy Motors Auto Service',
            'email'             => 'speedy@garage.lk',
            'password'          => Hash::make('Garage@1234'),
            'role'              => 'garage',
            'email_verified_at' => now(),
        ]);
        $speedyGarage = Garage::create([
            'user_id'        => $speedyUser->id,
            'name'           => 'Speedy Motors Auto Service',
            'address'        => '45/A, High Level Road, Nugegoda',
            'city'           => 'Nugegoda',
            'phone'          => '0112 851 234',
            'description'    => 'Full-service vehicle repair and maintenance centre with over 15 years of experience. Specialising in Japanese and Korean vehicles with genuine parts and certified technicians.',
            'specialization' => 'Engine Repair & Routine Maintenance',
            'is_active'      => true,
        ]);

        // ─── GARAGE 2 — AutoCare ──────────────────────────────────────────────
        $autocareUser = User::create([
            'name'              => 'AutoCare Service Centre',
            'email'             => 'autocare@garage.lk',
            'password'          => Hash::make('Garage@1234'),
            'role'              => 'garage',
            'email_verified_at' => now(),
        ]);
        $autocareGarage = Garage::create([
            'user_id'        => $autocareUser->id,
            'name'           => 'AutoCare Service Centre',
            'address'        => '12, Station Road, Maharagama',
            'city'           => 'Maharagama',
            'phone'          => '0112 743 567',
            'description'    => 'State-of-the-art diagnostic and repair facility offering comprehensive vehicle servicing. Equipped with latest computerised diagnostic tools for all vehicle makes and models.',
            'specialization' => 'Diagnostics, Electrical & AC Repairs',
            'is_active'      => true,
        ]);

        // ─── VEHICLE OWNER 1 — Kavindu Rajapaksa ─────────────────────────────
        $kavindu = User::create([
            'name'              => 'Kavindu Rajapaksa',
            'email'             => 'kavindu@gmail.com',
            'password'          => Hash::make('Owner@1234'),
            'role'              => 'vehicle_owner',
            'email_verified_at' => now(),
        ]);

        // Kavindu — Vehicle 1: Toyota Aqua
        $aqua = Vehicle::create([
            'user_id'       => $kavindu->id,
            'make'          => 'Toyota',
            'model'         => 'Aqua',
            'year'          => 2019,
            'mileage'       => 58400,
            'vin'           => 'JT2BF1FK5G0123456',
            'license_plate' => 'WP CAB-4521',
            'color'         => 'Silver',
            'fuel_type'     => 'petrol',
            'qr_token'      => Str::uuid(),
        ]);

        // Aqua — Fuel Logs
        $aquaFuelLogs = [
            ['date' => '2024-11-10', 'liters' => 35.5,  'cost' => 11715, 'km_reading' => 57250, 'km_per_liter' => 15.2, 'fuel_station' => 'Ceypetco Nugegoda',    'notes' => null],
            ['date' => '2024-12-05', 'liters' => 38.0,  'cost' => 12540, 'km_reading' => 57800, 'km_per_liter' => 14.5, 'fuel_station' => 'Lanka IOC Maharagama', 'notes' => null],
            ['date' => '2025-01-18', 'liters' => 40.2,  'cost' => 13266, 'km_reading' => 58100, 'km_per_liter' => 14.9, 'fuel_station' => 'Ceypetco Nugegoda',    'notes' => 'Full tank before long trip'],
            ['date' => '2025-03-02', 'liters' => 36.8,  'cost' => 12144, 'km_reading' => 58350, 'km_per_liter' => 15.1, 'fuel_station' => 'Lanka IOC Piliyandala', 'notes' => null],
        ];
        foreach ($aquaFuelLogs as $log) {
            FuelLog::create(array_merge($log, ['vehicle_id' => $aqua->id]));
        }

        // Aqua — Service Logs (names match MaintenanceSchedule rules for suggestions engine)
        $aquaServiceLogs = [
            // Oil change at 52,800 → next due 57,800 → 600km overdue → OVERDUE
            ['service_type' => 'Engine Oil & Filter Change',     'service_date' => '2024-08-12', 'mileage_at_service' => 52800, 'cost' => 4500,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Used 5W-30 fully synthetic oil'],
            // Tyre rotation at 48,200 → next due 58,200 → 200km overdue → OVERDUE
            ['service_type' => 'Tyre Rotation & Wheel Alignment','service_date' => '2024-06-05', 'mileage_at_service' => 48200, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Brake pad check at 43,450 → next due 58,450 → 50km remaining → DUE SOON
            ['service_type' => 'Brake Pad & Disc Inspection',    'service_date' => '2024-03-20', 'mileage_at_service' => 43450, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Front pads at 5mm, rear at 6mm'],
            // Air filter at 45,000 → next due 60,000 → 1,600km away → Upcoming
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2024-04-18', 'mileage_at_service' => 45000, 'cost' => 1800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ];
        foreach ($aquaServiceLogs as $log) {
            ServiceLog::create(array_merge($log, ['vehicle_id' => $aqua->id]));
        }

        // Kavindu — Vehicle 2: Honda Vezel
        $vezel = Vehicle::create([
            'user_id'       => $kavindu->id,
            'make'          => 'Honda',
            'model'         => 'Vezel',
            'year'          => 2021,
            'mileage'       => 32100,
            'vin'           => '5FNRL5H90MB123456',
            'license_plate' => 'WP CBE-7834',
            'color'         => 'White',
            'fuel_type'     => 'hybrid',
            'qr_token'      => Str::uuid(),
        ]);

        // ─── VEHICLE OWNER 2 — Dilani Wickramasinghe ─────────────────────────
        $dilani = User::create([
            'name'              => 'Dilani Wickramasinghe',
            'email'             => 'dilani@gmail.com',
            'password'          => Hash::make('Owner@1234'),
            'role'              => 'vehicle_owner',
            'email_verified_at' => now(),
        ]);

        // Dilani — Vehicle: Suzuki Swift
        $swift = Vehicle::create([
            'user_id'       => $dilani->id,
            'make'          => 'Suzuki',
            'model'         => 'Swift',
            'year'          => 2020,
            'mileage'       => 41200,
            'vin'           => 'JS1ZC11S00C123456',
            'license_plate' => 'CP KA-2210',
            'color'         => 'Red',
            'fuel_type'     => 'petrol',
            'qr_token'      => Str::uuid(),
        ]);

        // Swift — Fuel Logs
        $swiftFuelLogs = [
            ['date' => '2025-01-08', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 40500, 'km_per_liter' => 15.8, 'fuel_station' => 'Ceypetco Kandy',        'notes' => null],
            ['date' => '2025-02-14', 'liters' => 29.5, 'cost' => 9735,  'km_reading' => 40900, 'km_per_liter' => 16.1, 'fuel_station' => 'Lanka IOC Peradeniya',  'notes' => null],
            ['date' => '2025-03-22', 'liters' => 33.0, 'cost' => 10890, 'km_reading' => 41100, 'km_per_liter' => 15.5, 'fuel_station' => 'Ceypetco Kandy',        'notes' => 'Full tank'],
        ];
        foreach ($swiftFuelLogs as $log) {
            FuelLog::create(array_merge($log, ['vehicle_id' => $swift->id]));
        }

        // Swift — Service Logs (names match MaintenanceSchedule rules for suggestions engine)
        $swiftServiceLogs = [
            // Oil change at 36,000 → next due 41,000 → 200km overdue → OVERDUE
            ['service_type' => 'Engine Oil & Filter Change',     'service_date' => '2024-09-10', 'mileage_at_service' => 36000, 'cost' => 3800, 'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => 'Used Suzuki genuine 5W-30 oil'],
            // Air filter at 26,500 → next due 41,500 → 300km remaining → DUE SOON
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2024-01-15', 'mileage_at_service' => 26500, 'cost' => 1600, 'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => null],
            // Spark plugs at 12,000 → next due 42,000 → 800km away → Upcoming
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2023-04-20', 'mileage_at_service' => 12000, 'cost' => 4200, 'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'repair',      'notes' => 'Replaced all 4 NGK spark plugs'],
        ];
        foreach ($swiftServiceLogs as $log) {
            ServiceLog::create(array_merge($log, ['vehicle_id' => $swift->id]));
        }

        // ─── BOOKINGS ─────────────────────────────────────────────────────────
        // Booking 1: Kavindu Aqua → Speedy Motors (completed with invoice)
        Booking::create([
            'vehicle_id'     => $aqua->id,
            'garage_id'      => $speedyGarage->id,
            'booking_date'   => '2025-04-10',
            'booking_time'   => '09:00:00',
            'service_type'   => 'Full Service & Oil Change',
            'notes'          => 'Please check tyre pressure as well.',
            'status'         => 'completed',
            'invoice_amount' => 12500.00,
            'invoice_notes'  => 'Completed full service including oil change, filter replacement and brake fluid top-up.',
        ]);

        // Booking 2: Kavindu Vezel → AutoCare (confirmed)
        Booking::create([
            'vehicle_id'   => $vezel->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2025-04-25',
            'booking_time' => '10:30:00',
            'service_type' => 'AC Service & Diagnostic Check',
            'notes'        => 'AC is not cooling properly. Please run full diagnostic.',
            'status'       => 'confirmed',
        ]);

        // Booking 3: Dilani Swift → Speedy Motors (pending)
        Booking::create([
            'vehicle_id'   => $swift->id,
            'garage_id'    => $speedyGarage->id,
            'booking_date' => '2025-05-05',
            'booking_time' => '08:30:00',
            'service_type' => 'Engine Oil Change & Tyre Rotation',
            'notes'        => 'Due for regular service. Please check brake fluid level too.',
            'status'       => 'pending',
        ]);
    }
}
