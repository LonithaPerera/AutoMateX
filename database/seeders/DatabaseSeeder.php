<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Garage;
use App\Models\Booking;
use App\Models\FuelLog;
use App\Models\ServiceLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── MAINTENANCE SCHEDULE RULES ───────────────────────────────────────
        $this->call(MaintenanceScheduleSeeder::class);

        // ─── PARTS ────────────────────────────────────────────────────────────
        $this->call(PartSeeder::class);

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

        // ═════════════════════════════════════════════════════════════════════
        // VEHICLE OWNER 1 — Kavindu Rajapaksa
        // ═════════════════════════════════════════════════════════════════════
        $kavindu = User::create([
            'name'              => 'Kavindu Rajapaksa',
            'email'             => 'kavindu@gmail.com',
            'password'          => Hash::make('Owner@1234'),
            'role'              => 'vehicle_owner',
            'email_verified_at' => now(),
        ]);

        // ── Vehicle 1: Toyota Aqua (2019 · 58,400 km) ────────────────────────
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
        foreach ([
            ['date' => '2024-11-10', 'liters' => 35.5, 'cost' => 11715, 'km_reading' => 57250, 'km_per_liter' => 15.2, 'fuel_station' => 'Ceypetco Nugegoda',     'notes' => null],
            ['date' => '2024-12-05', 'liters' => 38.0, 'cost' => 12540, 'km_reading' => 57800, 'km_per_liter' => 14.5, 'fuel_station' => 'Lanka IOC Maharagama',  'notes' => null],
            ['date' => '2025-01-18', 'liters' => 40.2, 'cost' => 13266, 'km_reading' => 58100, 'km_per_liter' => 14.9, 'fuel_station' => 'Ceypetco Nugegoda',     'notes' => 'Full tank before long trip'],
            ['date' => '2025-03-02', 'liters' => 36.8, 'cost' => 12144, 'km_reading' => 58350, 'km_per_liter' => 15.1, 'fuel_station' => 'Lanka IOC Piliyandala', 'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $aqua->id]));

        foreach ([
            // Oil change at 52,800 → next 57,800 → OVERDUE by 600km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-08-12', 'mileage_at_service' => 52800, 'cost' => 4500,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Used 5W-30 fully synthetic oil'],
            // Tyre rotation at 48,200 → next 58,200 → OVERDUE by 200km
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-06-05', 'mileage_at_service' => 48200, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Brake pads at 43,450 → next 58,450 → DUE SOON (50km left)
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2024-03-20', 'mileage_at_service' => 43450, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Front pads at 5mm, rear at 6mm'],
            // Air filter at 45,000 → next 60,000 → Upcoming (1,600km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2024-04-18', 'mileage_at_service' => 45000, 'cost' => 1800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $aqua->id]));

        // ── Vehicle 2: Honda Vezel (2021 · 32,100 km) ────────────────────────
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
        foreach ([
            ['date' => '2024-10-15', 'liters' => 25.0, 'cost' => 8250,  'km_reading' => 31200, 'km_per_liter' => 19.2, 'fuel_station' => 'Lanka IOC Nugegoda',    'notes' => null],
            ['date' => '2024-12-20', 'liters' => 22.5, 'cost' => 7425,  'km_reading' => 31600, 'km_per_liter' => 20.0, 'fuel_station' => 'Ceypetco Maharagama',   'notes' => null],
            ['date' => '2025-02-08', 'liters' => 28.0, 'cost' => 9240,  'km_reading' => 31900, 'km_per_liter' => 18.6, 'fuel_station' => 'Lanka IOC Nugegoda',    'notes' => 'Full tank'],
            ['date' => '2025-04-01', 'liters' => 24.5, 'cost' => 8085,  'km_reading' => 32050, 'km_per_liter' => 19.8, 'fuel_station' => 'Ceypetco Maharagama',   'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $vezel->id]));

        foreach ([
            // Oil change at 27,000 → next 32,000 → OVERDUE by 100km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-07-18', 'mileage_at_service' => 27000, 'cost' => 4800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Honda genuine 0W-20 oil used'],
            // Tyre rotation at 22,200 → next 32,200 → DUE SOON (100km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-04-10', 'mileage_at_service' => 22200, 'cost' => 3500,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Air filter at 18,000 → next 33,000 → Upcoming (900km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2023-11-05', 'mileage_at_service' => 18000, 'cost' => 2200,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
            // Brake fluid at 12,500 → next 32,500 → DUE SOON (400km left)
            ['service_type' => 'Brake Fluid Change',              'service_date' => '2023-05-20', 'mileage_at_service' => 12500, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Replaced with Honda genuine DOT 3 fluid'],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $vezel->id]));

        // ── Vehicle 3: Toyota Prius (2016 · 78,500 km) ───────────────────────
        $prius = Vehicle::create([
            'user_id'       => $kavindu->id,
            'make'          => 'Toyota',
            'model'         => 'Prius',
            'year'          => 2016,
            'mileage'       => 78500,
            'vin'           => 'JTDKN3DU5G1234567',
            'license_plate' => 'WP KG-1823',
            'color'         => 'White',
            'fuel_type'     => 'hybrid',
            'qr_token'      => Str::uuid(),
        ]);
        foreach ([
            ['date' => '2024-09-10', 'liters' => 30.0, 'cost' => 9900,  'km_reading' => 76800, 'km_per_liter' => 21.3, 'fuel_station' => 'Ceypetco Nugegoda',    'notes' => null],
            ['date' => '2024-11-22', 'liters' => 28.5, 'cost' => 9405,  'km_reading' => 77400, 'km_per_liter' => 20.7, 'fuel_station' => 'Lanka IOC Maharagama', 'notes' => null],
            ['date' => '2025-01-15', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 78000, 'km_per_liter' => 21.5, 'fuel_station' => 'Ceypetco Nugegoda',    'notes' => 'Full tank before Kandy trip'],
            ['date' => '2025-03-10', 'liters' => 27.0, 'cost' => 8910,  'km_reading' => 78350, 'km_per_liter' => 20.4, 'fuel_station' => 'Lanka IOC Maharagama', 'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $prius->id]));

        foreach ([
            // Oil change at 72,800 → next 77,800 → OVERDUE by 700km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-06-25', 'mileage_at_service' => 72800, 'cost' => 4500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Toyota genuine 5W-30 hybrid oil'],
            // Brake pads at 63,700 → next 78,700 → DUE SOON (200km left)
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2023-08-14', 'mileage_at_service' => 63700, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'inspection',  'notes' => 'Pads at 4mm — monitor closely'],
            // Tyre rotation at 70,000 → next 80,000 → Upcoming (1,500km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-02-20', 'mileage_at_service' => 70000, 'cost' => 3800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Air filter at 65,000 → next 80,000 → Upcoming (1,500km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2023-10-08', 'mileage_at_service' => 65000, 'cost' => 2000,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
            // Spark plugs at 50,000 → next 80,000 → Upcoming (1,500km left)
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2022-09-12', 'mileage_at_service' => 50000, 'cost' => 6500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Replaced all 4 Denso iridium plugs'],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $prius->id]));

        // ── Vehicle 4: Mazda Demio (2018 · 45,200 km) ────────────────────────
        $demio = Vehicle::create([
            'user_id'       => $kavindu->id,
            'make'          => 'Mazda',
            'model'         => 'Demio',
            'year'          => 2018,
            'mileage'       => 45200,
            'vin'           => 'JMZDJ188200123456',
            'license_plate' => 'WP LA-3345',
            'color'         => 'Blue',
            'fuel_type'     => 'petrol',
            'qr_token'      => Str::uuid(),
        ]);
        foreach ([
            ['date' => '2024-10-05', 'liters' => 38.0, 'cost' => 12540, 'km_reading' => 44000, 'km_per_liter' => 15.5, 'fuel_station' => 'Ceypetco Maharagama',   'notes' => null],
            ['date' => '2024-12-18', 'liters' => 35.5, 'cost' => 11715, 'km_reading' => 44600, 'km_per_liter' => 16.1, 'fuel_station' => 'Lanka IOC Piliyandala', 'notes' => null],
            ['date' => '2025-02-22', 'liters' => 40.0, 'cost' => 13200, 'km_reading' => 45000, 'km_per_liter' => 15.8, 'fuel_station' => 'Ceypetco Maharagama',   'notes' => 'Full tank'],
            ['date' => '2025-04-10', 'liters' => 36.0, 'cost' => 11880, 'km_reading' => 45150, 'km_per_liter' => 16.0, 'fuel_station' => 'Lanka IOC Nugegoda',    'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $demio->id]));

        foreach ([
            // Oil change at 40,000 → next 45,000 → OVERDUE by 200km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-07-30', 'mileage_at_service' => 40000, 'cost' => 4200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Mazda genuine 5W-30 SKYACTIV oil'],
            // Air filter at 30,500 → next 45,500 → DUE SOON (300km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2024-01-22', 'mileage_at_service' => 30500, 'cost' => 1900,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Tyre rotation at 36,000 → next 46,000 → Upcoming (800km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-04-05', 'mileage_at_service' => 36000, 'cost' => 3500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
            // Brake pads at 30,800 → next 45,800 → Upcoming (600km left)
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2024-02-14', 'mileage_at_service' => 30800, 'cost' => 2600,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'All pads in good condition, 6mm remaining'],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $demio->id]));

        // ═════════════════════════════════════════════════════════════════════
        // VEHICLE OWNER 2 — Dilani Wickramasinghe
        // ═════════════════════════════════════════════════════════════════════
        $dilani = User::create([
            'name'              => 'Dilani Wickramasinghe',
            'email'             => 'dilani@gmail.com',
            'password'          => Hash::make('Owner@1234'),
            'role'              => 'vehicle_owner',
            'email_verified_at' => now(),
        ]);

        // ── Vehicle 1: Suzuki Swift (2020 · 41,200 km) ───────────────────────
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
        foreach ([
            ['date' => '2025-01-08', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 40500, 'km_per_liter' => 15.8, 'fuel_station' => 'Ceypetco Kandy',       'notes' => null],
            ['date' => '2025-02-14', 'liters' => 29.5, 'cost' => 9735,  'km_reading' => 40900, 'km_per_liter' => 16.1, 'fuel_station' => 'Lanka IOC Peradeniya', 'notes' => null],
            ['date' => '2025-03-22', 'liters' => 33.0, 'cost' => 10890, 'km_reading' => 41100, 'km_per_liter' => 15.5, 'fuel_station' => 'Ceypetco Kandy',       'notes' => 'Full tank'],
            ['date' => '2025-04-28', 'liters' => 30.5, 'cost' => 10065, 'km_reading' => 41150, 'km_per_liter' => 16.2, 'fuel_station' => 'Lanka IOC Kandy',      'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $swift->id]));

        foreach ([
            // Oil change at 36,000 → next 41,000 → OVERDUE by 200km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-09-10', 'mileage_at_service' => 36000, 'cost' => 3800,  'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => 'Used Suzuki genuine 5W-30 oil'],
            // Air filter at 26,500 → next 41,500 → DUE SOON (300km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2024-01-15', 'mileage_at_service' => 26500, 'cost' => 1600,  'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => null],
            // Spark plugs at 12,000 → next 42,000 → Upcoming (800km left)
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2023-04-20', 'mileage_at_service' => 12000, 'cost' => 4200,  'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'repair',      'notes' => 'Replaced all 4 NGK spark plugs'],
            // Tyre rotation at 31,500 → next 41,500 → DUE SOON (300km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-08-05', 'mileage_at_service' => 31500, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda',         'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $swift->id]));

        // ── Vehicle 2: Nissan Leaf (2019 · 28,600 km · Electric) ─────────────
        $leaf = Vehicle::create([
            'user_id'       => $dilani->id,
            'make'          => 'Nissan',
            'model'         => 'Leaf',
            'year'          => 2019,
            'mileage'       => 28600,
            'vin'           => '1N4AZ1CP5KC306789',
            'license_plate' => 'CP KF-5521',
            'color'         => 'White',
            'fuel_type'     => 'electric',
            'qr_token'      => Str::uuid(),
        ]);
        // Electric vehicle — no fuel logs
        foreach ([
            // Battery check at 8,800 → next 28,800 → DUE SOON (200km left)
            ['service_type' => 'Battery & Electrical Check',      'service_date' => '2023-06-10', 'mileage_at_service' =>  8800, 'cost' => 3500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Battery health at 94% SOH. All cells within normal range'],
            // Brake fluid at 8,700 → next 28,700 → DUE SOON (100km left)
            ['service_type' => 'Brake Fluid Change',              'service_date' => '2023-05-15', 'mileage_at_service' =>  8700, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Replaced with Nissan genuine DOT 3 brake fluid'],
            // Brake pads at 14,000 → next 29,000 → Upcoming (400km left)
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2024-01-22', 'mileage_at_service' => 14000, 'cost' => 2500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Regenerative braking reducing wear. Pads at 8mm'],
            // Tyre rotation at 19,200 → next 29,200 → Upcoming (600km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-06-18', 'mileage_at_service' => 19200, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $leaf->id]));

        // ── Vehicle 3: Honda Fit (2017 · 63,800 km) ──────────────────────────
        $fit = Vehicle::create([
            'user_id'       => $dilani->id,
            'make'          => 'Honda',
            'model'         => 'Fit',
            'year'          => 2017,
            'mileage'       => 63800,
            'vin'           => 'MRHGK6750HJ123456',
            'license_plate' => 'CP LA-8832',
            'color'         => 'Silver',
            'fuel_type'     => 'petrol',
            'qr_token'      => Str::uuid(),
        ]);
        foreach ([
            ['date' => '2024-09-25', 'liters' => 36.0, 'cost' => 11880, 'km_reading' => 62500, 'km_per_liter' => 15.3, 'fuel_station' => 'Ceypetco Kandy',       'notes' => null],
            ['date' => '2024-11-30', 'liters' => 33.5, 'cost' => 11055, 'km_reading' => 63100, 'km_per_liter' => 15.9, 'fuel_station' => 'Lanka IOC Peradeniya', 'notes' => null],
            ['date' => '2025-02-15', 'liters' => 38.0, 'cost' => 12540, 'km_reading' => 63600, 'km_per_liter' => 15.6, 'fuel_station' => 'Ceypetco Kandy',       'notes' => 'Full tank'],
            ['date' => '2025-04-08', 'liters' => 34.5, 'cost' => 11385, 'km_reading' => 63750, 'km_per_liter' => 15.8, 'fuel_station' => 'Lanka IOC Kandy',      'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $fit->id]));

        foreach ([
            // Oil change at 58,600 → next 63,600 → OVERDUE by 200km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-07-12', 'mileage_at_service' => 58600, 'cost' => 4000,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Honda genuine 0W-20 oil'],
            // Air filter at 49,000 → next 64,000 → DUE SOON (200km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2023-10-05', 'mileage_at_service' => 49000, 'cost' => 1800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Tyre rotation at 54,200 → next 64,200 → Upcoming (400km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-03-18', 'mileage_at_service' => 54200, 'cost' => 3200,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
            // Spark plugs at 34,500 → next 64,500 → Upcoming (700km left)
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2022-05-10', 'mileage_at_service' => 34500, 'cost' => 5800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'repair',      'notes' => 'Replaced all 4 NGK iridium plugs'],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $fit->id]));

        // ── Vehicle 4: Toyota Vitz (2018 · 52,300 km) ────────────────────────
        $vitz = Vehicle::create([
            'user_id'       => $dilani->id,
            'make'          => 'Toyota',
            'model'         => 'Vitz',
            'year'          => 2018,
            'mileage'       => 52300,
            'vin'           => 'KSP130-0123456',
            'license_plate' => 'CP MB-4410',
            'color'         => 'Black',
            'fuel_type'     => 'petrol',
            'qr_token'      => Str::uuid(),
        ]);
        foreach ([
            ['date' => '2024-10-12', 'liters' => 34.0, 'cost' => 11220, 'km_reading' => 51000, 'km_per_liter' => 15.6, 'fuel_station' => 'Ceypetco Kandy',       'notes' => null],
            ['date' => '2024-12-25', 'liters' => 31.5, 'cost' => 10395, 'km_reading' => 51600, 'km_per_liter' => 16.2, 'fuel_station' => 'Lanka IOC Peradeniya', 'notes' => null],
            ['date' => '2025-02-18', 'liters' => 36.0, 'cost' => 11880, 'km_reading' => 52100, 'km_per_liter' => 15.9, 'fuel_station' => 'Ceypetco Kandy',       'notes' => 'Full tank before Colombo trip'],
            ['date' => '2025-04-15', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 52250, 'km_per_liter' => 16.0, 'fuel_station' => 'Lanka IOC Kandy',      'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $vitz->id]));

        foreach ([
            // Oil change at 47,100 → next 52,100 → OVERDUE by 200km
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2024-06-20', 'mileage_at_service' => 47100, 'cost' => 3800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Toyota genuine 5W-30 oil'],
            // Brake pads at 37,500 → next 52,500 → DUE SOON (200km left)
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2023-09-14', 'mileage_at_service' => 37500, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Front pads at 4mm, replace soon'],
            // Air filter at 38,000 → next 53,000 → Upcoming (700km left)
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2023-10-28', 'mileage_at_service' => 38000, 'cost' => 1700,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            // Tyre rotation at 43,000 → next 53,000 → Upcoming (700km left)
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2024-02-10', 'mileage_at_service' => 43000, 'cost' => 3000,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $vitz->id]));

        // ─── BOOKINGS ─────────────────────────────────────────────────────────
        // Toyota Aqua → Speedy Motors (completed · March 2026 → shows on chart)
        Booking::create([
            'vehicle_id'     => $aqua->id,
            'garage_id'      => $speedyGarage->id,
            'booking_date'   => '2026-03-15',
            'booking_time'   => '09:00:00',
            'service_type'   => 'Full Service & Oil Change',
            'notes'          => 'Please check tyre pressure as well.',
            'status'         => 'completed',
            'invoice_amount' => 12500.00,
            'invoice_notes'  => 'Completed full service including oil change, filter replacement and brake fluid top-up.',
            'invoice_number' => 'INV-2026-0001',
            'invoice_date'   => '2026-03-15',
        ]);
        // Honda Vezel → AutoCare (confirmed · upcoming)
        Booking::create([
            'vehicle_id'   => $vezel->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-05-20',
            'booking_time' => '10:30:00',
            'service_type' => 'AC Service & Diagnostic Check',
            'notes'        => 'AC is not cooling properly. Please run full diagnostic.',
            'status'       => 'confirmed',
        ]);
        // Suzuki Swift → Speedy Motors (pending · upcoming)
        Booking::create([
            'vehicle_id'   => $swift->id,
            'garage_id'    => $speedyGarage->id,
            'booking_date' => '2026-05-10',
            'booking_time' => '08:30:00',
            'service_type' => 'Engine Oil Change & Tyre Rotation',
            'notes'        => 'Due for regular service. Please check brake fluid level too.',
            'status'       => 'pending',
        ]);
        // Toyota Prius → AutoCare (confirmed · upcoming)
        Booking::create([
            'vehicle_id'   => $prius->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-05-28',
            'booking_time' => '09:30:00',
            'service_type' => 'Engine Oil Change & Hybrid System Check',
            'notes'        => 'Overdue for oil change. Also check hybrid battery cooling fan.',
            'status'       => 'confirmed',
        ]);
        // Nissan Leaf → AutoCare (pending · upcoming)
        Booking::create([
            'vehicle_id'   => $leaf->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-05-18',
            'booking_time' => '11:00:00',
            'service_type' => 'Battery Health Check & Brake Fluid Change',
            'notes'        => 'Check battery SOH and replace brake fluid.',
            'status'       => 'pending',
        ]);
        // Honda Fit → Speedy Motors (completed · May 2026 → shows on chart + this month)
        Booking::create([
            'vehicle_id'     => $fit->id,
            'garage_id'      => $speedyGarage->id,
            'booking_date'   => '2026-05-08',
            'booking_time'   => '08:00:00',
            'service_type'   => 'Engine Oil Change & Air Filter Replacement',
            'notes'          => 'Overdue for oil change and air filter is due soon.',
            'status'         => 'completed',
            'invoice_amount' => 8500.00,
            'invoice_notes'  => 'Completed oil change with Honda genuine 0W-20, replaced air filter. Vehicle running smoothly.',
            'invoice_number' => 'INV-2026-0002',
            'invoice_date'   => '2026-05-08',
        ]);

        // Mazda Demio → AutoCare (cancelled · past)
        Booking::create([
            'vehicle_id'   => $demio->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-02-10',
            'booking_time' => '10:00:00',
            'service_type' => 'Full Service & Wheel Alignment',
            'notes'        => 'Need full service and alignment checked.',
            'status'       => 'cancelled',
        ]);

        // Toyota Vitz → Speedy Motors (cancelled · past)
        Booking::create([
            'vehicle_id'   => $vitz->id,
            'garage_id'    => $speedyGarage->id,
            'booking_date' => '2026-01-20',
            'booking_time' => '09:00:00',
            'service_type' => 'Engine Oil Change & Brake Pad Inspection',
            'notes'        => 'Overdue oil change and brake pads need checking.',
            'status'       => 'cancelled',
        ]);
    }
}
