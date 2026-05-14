<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\FuelLog;
use App\Models\Garage;
use App\Models\Rating;
use App\Models\ServiceLog;
use App\Models\TripLog;
use App\Models\User;
use App\Models\Vehicle;
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
            'avatar'            => 'avatars/zG7Z8MI3DCQUGmJtClGSuZK7blQwQYES4v3Ma2tO.jpg',
        ]);

        // ─── GARAGE 1 — Speedy Motors ─────────────────────────────────────────
        $speedyUser = User::create([
            'name'              => 'Speedy Motors Auto Service',
            'email'             => 'speedy@garage.lk',
            'password'          => Hash::make('Garage@1234'),
            'role'              => 'garage',
            'email_verified_at' => now(),
            'avatar'            => 'avatars/ixFv1bV2FUG0nGhyGnIeonxmzIpm0RbaxMJSIig3.jpg',
        ]);
        $speedyGarage = Garage::create([
            'user_id'         => $speedyUser->id,
            'name'            => 'Speedy Motors Auto Service',
            'address'         => '45/A, High Level Road, Nugegoda',
            'city'            => 'Nugegoda',
            'phone'           => '0112 851 234',
            'description'     => 'Full-service vehicle repair and maintenance centre with over 15 years of experience. Specialising in Japanese and Korean vehicles with genuine parts and certified technicians.',
            'specialization'  => 'Engine Repair & Routine Maintenance',
            'is_active'       => true,
            'monthly_target'  => 150000,
            'photo'           => 'garages/eCxt6AwX0uuAVoXkqDLfdpWW79tXvqhVZs5CV1Mj.jpg',
            'working_hours'   => json_encode([
                'mon' => ['closed' => false, 'open' => '08:00', 'close' => '18:00'],
                'tue' => ['closed' => false, 'open' => '08:00', 'close' => '18:00'],
                'wed' => ['closed' => false, 'open' => '08:00', 'close' => '18:00'],
                'thu' => ['closed' => false, 'open' => '08:00', 'close' => '18:00'],
                'fri' => ['closed' => false, 'open' => '08:00', 'close' => '18:00'],
                'sat' => ['closed' => false, 'open' => '08:00', 'close' => '15:00'],
                'sun' => ['closed' => true,  'open' => '09:00', 'close' => '13:00'],
            ]),
        ]);

        // ─── GARAGE 2 — AutoCare ──────────────────────────────────────────────
        $autocareUser = User::create([
            'name'              => 'AutoCare Service Centre',
            'email'             => 'autocare@garage.lk',
            'password'          => Hash::make('Garage@1234'),
            'role'              => 'garage',
            'email_verified_at' => now(),
            'avatar'            => 'avatars/Q8spRVVFhM7TPGj3rhzrBjmwSZkGM1vmhllNdR5e.jpg',
        ]);
        $autocareGarage = Garage::create([
            'user_id'         => $autocareUser->id,
            'name'            => 'AutoCare Service Centre',
            'address'         => '12, Station Road, Maharagama',
            'city'            => 'Maharagama',
            'phone'           => '0112 743 567',
            'description'     => 'State-of-the-art diagnostic and repair facility offering comprehensive vehicle servicing. Equipped with latest computerised diagnostic tools for all vehicle makes and models.',
            'specialization'  => 'Diagnostics, Electrical & AC Repairs',
            'is_active'       => true,
            'monthly_target'  => 200000,
            'photo'           => 'garages/1i0fhPYgvwz3osxYrO3waOn3ZO4tSbHEPiXWyRS9.jpg',
            'working_hours'   => json_encode([
                'mon' => ['closed' => false, 'open' => '07:30', 'close' => '18:30'],
                'tue' => ['closed' => false, 'open' => '07:30', 'close' => '18:30'],
                'wed' => ['closed' => false, 'open' => '07:30', 'close' => '18:30'],
                'thu' => ['closed' => false, 'open' => '07:30', 'close' => '18:30'],
                'fri' => ['closed' => false, 'open' => '07:30', 'close' => '18:30'],
                'sat' => ['closed' => false, 'open' => '08:00', 'close' => '16:00'],
                'sun' => ['closed' => true,  'open' => '09:00', 'close' => '13:00'],
            ]),
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
            'avatar'            => 'avatars/4GpUCFpECg2tBtITq83u6ZyLKF7MXRnioJbssUoo.jpg',
        ]);

        // ── Vehicle 1: Toyota Aqua (2019 · 58,400 km) ────────────────────────
        $aqua = Vehicle::create([
            'user_id'              => $kavindu->id,
            'make'                 => 'Toyota',
            'model'                => 'Aqua',
            'year'                 => 2019,
            'mileage'              => 58400,
            'vin'                  => 'JT2BF1FK5G0123456',
            'license_plate'        => 'WP CAB-4521',
            'color'                => 'Silver',
            'fuel_type'            => 'petrol',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/AcOQyBspMMXORV9yW2ue0d7sPBkEShVk7UfNir4G.jpg',
            'notes'                => 'Daily commuter. Regularly serviced at Speedy Motors. Excellent fuel economy for city driving.',
            'insurance_expiry'     => '2026-06-05',
            'registration_expiry'  => '2026-12-15',
            'emission_due'         => '2026-09-20',
        ]);
        foreach ([
            ['date' => '2025-09-10', 'liters' => 35.5, 'cost' => 11715, 'km_reading' => 57250, 'km_per_liter' => 15.2, 'fuel_station' => 'Ceypetco Nugegoda',     'notes' => null],
            ['date' => '2025-11-15', 'liters' => 38.0, 'cost' => 12540, 'km_reading' => 57800, 'km_per_liter' => 14.5, 'fuel_station' => 'Lanka IOC Maharagama',  'notes' => null],
            ['date' => '2026-01-28', 'liters' => 40.2, 'cost' => 13266, 'km_reading' => 58100, 'km_per_liter' => 14.9, 'fuel_station' => 'Ceypetco Nugegoda',     'notes' => 'Full tank before long trip'],
            ['date' => '2026-04-08', 'liters' => 36.8, 'cost' => 12144, 'km_reading' => 58350, 'km_per_liter' => 15.1, 'fuel_station' => 'Lanka IOC Piliyandala', 'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $aqua->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-08-12', 'mileage_at_service' => 52800, 'cost' => 4500,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Used 5W-30 fully synthetic oil'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2025-06-05', 'mileage_at_service' => 48200, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2025-03-20', 'mileage_at_service' => 43450, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Front pads at 5mm, rear at 6mm'],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2026-03-10', 'mileage_at_service' => 45000, 'cost' => 1800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $aqua->id]));

        foreach ([
            ['trip_date' => '2026-03-05', 'start_km' => 57800, 'end_km' => 57925, 'purpose' => 'personal',  'notes' => 'Trip to Kandy for family visit'],
            ['trip_date' => '2026-03-28', 'start_km' => 57925, 'end_km' => 58010, 'purpose' => 'business',  'notes' => 'Client meeting in Nugegoda'],
            ['trip_date' => '2026-04-15', 'start_km' => 58100, 'end_km' => 58250, 'purpose' => 'personal',  'notes' => 'Weekend trip to Galle'],
            ['trip_date' => '2026-05-02', 'start_km' => 58300, 'end_km' => 58395, 'purpose' => 'personal',  'notes' => 'Family outing'],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $aqua->id]));

        // ── Vehicle 2: Honda Vezel (2021 · 32,100 km) ────────────────────────
        $vezel = Vehicle::create([
            'user_id'              => $kavindu->id,
            'make'                 => 'Honda',
            'model'                => 'Vezel',
            'year'                 => 2021,
            'mileage'              => 32100,
            'vin'                  => '5FNRL5H90MB123456',
            'license_plate'        => 'WP CBE-7834',
            'color'                => 'White',
            'fuel_type'            => 'hybrid',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/PDzHYCwSwoNskRi3UBnpNt7AbZf6FW4iEh0vapS7.jpg',
            'notes'                => 'Weekend family car. Hybrid system well maintained. Prefer Honda genuine parts only.',
            'insurance_expiry'     => '2026-11-10',
            'registration_expiry'  => '2026-04-25',
            'emission_due'         => '2026-08-15',
        ]);
        foreach ([
            ['date' => '2025-09-20', 'liters' => 25.0, 'cost' => 8250,  'km_reading' => 31200, 'km_per_liter' => 19.2, 'fuel_station' => 'Lanka IOC Nugegoda',  'notes' => null],
            ['date' => '2025-11-25', 'liters' => 22.5, 'cost' => 7425,  'km_reading' => 31600, 'km_per_liter' => 20.0, 'fuel_station' => 'Ceypetco Maharagama', 'notes' => null],
            ['date' => '2026-01-30', 'liters' => 28.0, 'cost' => 9240,  'km_reading' => 31900, 'km_per_liter' => 18.6, 'fuel_station' => 'Lanka IOC Nugegoda',  'notes' => 'Full tank'],
            ['date' => '2026-04-01', 'liters' => 24.5, 'cost' => 8085,  'km_reading' => 32050, 'km_per_liter' => 19.8, 'fuel_station' => 'Ceypetco Maharagama', 'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $vezel->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-07-18', 'mileage_at_service' => 27000, 'cost' => 4800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Honda genuine 0W-20 oil used'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2025-04-10', 'mileage_at_service' => 22200, 'cost' => 3500,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2025-11-05', 'mileage_at_service' => 18000, 'cost' => 2200,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Brake Fluid Change',              'service_date' => '2026-02-20', 'mileage_at_service' => 12500, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Replaced with Honda genuine DOT 3 fluid'],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $vezel->id]));

        foreach ([
            ['trip_date' => '2026-03-10', 'start_km' => 31600, 'end_km' => 31750, 'purpose' => 'personal',  'notes' => 'Weekend drive to Negombo'],
            ['trip_date' => '2026-04-02', 'start_km' => 31800, 'end_km' => 31920, 'purpose' => 'business',  'notes' => 'Office meeting outstation'],
            ['trip_date' => '2026-04-20', 'start_km' => 31950, 'end_km' => 32050, 'purpose' => 'personal',  'notes' => null],
            ['trip_date' => '2026-05-05', 'start_km' => 32060, 'end_km' => 32095, 'purpose' => 'personal',  'notes' => 'School run and errands'],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $vezel->id]));

        // ── Vehicle 3: Toyota Prius (2016 · 78,500 km) ───────────────────────
        $prius = Vehicle::create([
            'user_id'              => $kavindu->id,
            'make'                 => 'Toyota',
            'model'                => 'Prius',
            'year'                 => 2016,
            'mileage'              => 78500,
            'vin'                  => 'JTDKN3DU5G1234567',
            'license_plate'        => 'WP KG-1823',
            'color'                => 'White',
            'fuel_type'            => 'hybrid',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/m6RjH9jBBJ8o0UHQhgGYojwyOrzk6cU5qFsdY7gR.jpg',
            'notes'                => 'Long-distance traveller. Hybrid battery recently checked and in good health. Excellent highway fuel economy.',
            'insurance_expiry'     => '2026-10-30',
            'registration_expiry'  => '2026-11-15',
            'emission_due'         => '2026-12-20',
        ]);
        foreach ([
            ['date' => '2025-09-10', 'liters' => 30.0, 'cost' => 9900,  'km_reading' => 76800, 'km_per_liter' => 21.3, 'fuel_station' => 'Ceypetco Nugegoda',    'notes' => null],
            ['date' => '2025-11-22', 'liters' => 28.5, 'cost' => 9405,  'km_reading' => 77400, 'km_per_liter' => 20.7, 'fuel_station' => 'Lanka IOC Maharagama', 'notes' => null],
            ['date' => '2026-01-15', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 78000, 'km_per_liter' => 21.5, 'fuel_station' => 'Ceypetco Nugegoda',    'notes' => 'Full tank before Kandy trip'],
            ['date' => '2026-04-10', 'liters' => 27.0, 'cost' => 8910,  'km_reading' => 78350, 'km_per_liter' => 20.4, 'fuel_station' => 'Lanka IOC Maharagama', 'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $prius->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-06-25', 'mileage_at_service' => 72800, 'cost' => 4500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Toyota genuine 5W-30 hybrid oil'],
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2024-08-14', 'mileage_at_service' => 63700, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'inspection',  'notes' => 'Pads at 4mm — monitor closely'],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2025-10-08', 'mileage_at_service' => 65000, 'cost' => 2000,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2023-09-12', 'mileage_at_service' => 50000, 'cost' => 6500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Replaced all 4 Denso iridium plugs'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2026-02-20', 'mileage_at_service' => 70000, 'cost' => 3800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $prius->id]));

        foreach ([
            ['trip_date' => '2026-02-20', 'start_km' => 77800, 'end_km' => 77980, 'purpose' => 'business',  'notes' => 'Outstation business trip'],
            ['trip_date' => '2026-03-15', 'start_km' => 78000, 'end_km' => 78180, 'purpose' => 'personal',  'notes' => 'Colombo to Galle family trip'],
            ['trip_date' => '2026-04-10', 'start_km' => 78200, 'end_km' => 78380, 'purpose' => 'business',  'notes' => 'Client site visits'],
            ['trip_date' => '2026-05-01', 'start_km' => 78390, 'end_km' => 78490, 'purpose' => 'personal',  'notes' => null],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $prius->id]));

        // ── Vehicle 4: Mazda Demio (2018 · 45,200 km) ────────────────────────
        $demio = Vehicle::create([
            'user_id'              => $kavindu->id,
            'make'                 => 'Mazda',
            'model'                => 'Demio',
            'year'                 => 2018,
            'mileage'              => 45200,
            'vin'                  => 'JMZDJ188200123456',
            'license_plate'        => 'WP LA-3345',
            'color'                => 'Blue',
            'fuel_type'            => 'petrol',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/QMUfQsiXl5IIlWHitVYJjghwq2g7lzQes4rp55HN.jpg',
            'notes'                => 'City car used for short daily trips. SKYACTIV engine performs well. Upcoming emission check needed.',
            'insurance_expiry'     => '2026-09-05',
            'registration_expiry'  => '2026-07-10',
            'emission_due'         => '2026-05-28',
        ]);
        foreach ([
            ['date' => '2025-10-05', 'liters' => 38.0, 'cost' => 12540, 'km_reading' => 44000, 'km_per_liter' => 15.5, 'fuel_station' => 'Ceypetco Maharagama',   'notes' => null],
            ['date' => '2025-12-18', 'liters' => 35.5, 'cost' => 11715, 'km_reading' => 44600, 'km_per_liter' => 16.1, 'fuel_station' => 'Lanka IOC Piliyandala', 'notes' => null],
            ['date' => '2026-02-22', 'liters' => 40.0, 'cost' => 13200, 'km_reading' => 45000, 'km_per_liter' => 15.8, 'fuel_station' => 'Ceypetco Maharagama',   'notes' => 'Full tank'],
            ['date' => '2026-04-28', 'liters' => 36.0, 'cost' => 11880, 'km_reading' => 45150, 'km_per_liter' => 16.0, 'fuel_station' => 'Lanka IOC Nugegoda',    'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $demio->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-07-30', 'mileage_at_service' => 40000, 'cost' => 4200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Mazda genuine 5W-30 SKYACTIV oil'],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2025-01-22', 'mileage_at_service' => 30500, 'cost' => 1900,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2025-02-14', 'mileage_at_service' => 30800, 'cost' => 2600,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'All pads in good condition, 6mm remaining'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2026-04-05', 'mileage_at_service' => 36000, 'cost' => 3500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $demio->id]));

        foreach ([
            ['trip_date' => '2026-03-20', 'start_km' => 44900, 'end_km' => 45020, 'purpose' => 'personal',  'notes' => 'Weekend shopping trip'],
            ['trip_date' => '2026-04-08', 'start_km' => 45050, 'end_km' => 45140, 'purpose' => 'business',  'notes' => 'Delivery run'],
            ['trip_date' => '2026-04-28', 'start_km' => 45150, 'end_km' => 45195, 'purpose' => 'personal',  'notes' => null],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $demio->id]));

        // ═════════════════════════════════════════════════════════════════════
        // VEHICLE OWNER 2 — Dilani Wickramasinghe
        // ═════════════════════════════════════════════════════════════════════
        $dilani = User::create([
            'name'              => 'Dilani Wickramasinghe',
            'email'             => 'dilani@gmail.com',
            'password'          => Hash::make('Owner@1234'),
            'role'              => 'vehicle_owner',
            'email_verified_at' => now(),
            'avatar'            => 'avatars/xgnstU8x69ELe6A2sfVGGGc89wnnCQRDvyV99CrC.jpg',
        ]);

        // ── Vehicle 5: Suzuki Swift (2020 · 41,200 km) ───────────────────────
        $swift = Vehicle::create([
            'user_id'              => $dilani->id,
            'make'                 => 'Suzuki',
            'model'                => 'Swift',
            'year'                 => 2020,
            'mileage'              => 41200,
            'vin'                  => 'JS1ZC11S00C123456',
            'license_plate'        => 'CP KA-2210',
            'color'                => 'Red',
            'fuel_type'            => 'petrol',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/JG8Rf27F66Wj4xgV0twl3WjH2AhfxU1zxq2IpHuj.jpg',
            'notes'                => 'Main family vehicle used for school runs and shopping. Insurance renewal overdue — needs attention.',
            'insurance_expiry'     => '2026-05-02',
            'registration_expiry'  => '2026-10-15',
            'emission_due'         => '2026-11-05',
        ]);
        foreach ([
            ['date' => '2025-10-08', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 40500, 'km_per_liter' => 15.8, 'fuel_station' => 'Ceypetco Kandy',       'notes' => null],
            ['date' => '2025-12-14', 'liters' => 29.5, 'cost' => 9735,  'km_reading' => 40900, 'km_per_liter' => 16.1, 'fuel_station' => 'Lanka IOC Peradeniya', 'notes' => null],
            ['date' => '2026-02-22', 'liters' => 33.0, 'cost' => 10890, 'km_reading' => 41100, 'km_per_liter' => 15.5, 'fuel_station' => 'Ceypetco Kandy',       'notes' => 'Full tank'],
            ['date' => '2026-04-28', 'liters' => 30.5, 'cost' => 10065, 'km_reading' => 41150, 'km_per_liter' => 16.2, 'fuel_station' => 'Lanka IOC Kandy',      'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $swift->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-09-10', 'mileage_at_service' => 36000, 'cost' => 3800,  'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => 'Used Suzuki genuine 5W-30 oil'],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2025-01-15', 'mileage_at_service' => 26500, 'cost' => 1600,  'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2025-08-05', 'mileage_at_service' => 31500, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda',         'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2026-03-20', 'mileage_at_service' => 12000, 'cost' => 4200,  'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'repair',      'notes' => 'Replaced all 4 NGK spark plugs'],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $swift->id]));

        foreach ([
            ['trip_date' => '2026-02-28', 'start_km' => 40500, 'end_km' => 40680, 'purpose' => 'personal',  'notes' => 'Trip to Colombo for shopping'],
            ['trip_date' => '2026-03-25', 'start_km' => 40750, 'end_km' => 40880, 'purpose' => 'business',  'notes' => 'Office commute — weekly'],
            ['trip_date' => '2026-04-18', 'start_km' => 40950, 'end_km' => 41080, 'purpose' => 'personal',  'notes' => 'School trip pickup'],
            ['trip_date' => '2026-05-08', 'start_km' => 41100, 'end_km' => 41195, 'purpose' => 'personal',  'notes' => null],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $swift->id]));

        // ── Vehicle 6: Nissan Leaf (2019 · 28,600 km · Electric) ─────────────
        $leaf = Vehicle::create([
            'user_id'              => $dilani->id,
            'make'                 => 'Nissan',
            'model'                => 'Leaf',
            'year'                 => 2019,
            'mileage'              => 28600,
            'vin'                  => '1N4AZ1CP5KC306789',
            'license_plate'        => 'CP KF-5521',
            'color'                => 'White',
            'fuel_type'            => 'electric',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/p77HrLVi6YeInwXwryqwnvWgLMdukXZrHZic7Stp.jpg',
            'notes'                => 'Electric vehicle charged at home overnight. Battery health at 94% SOH. Regenerative braking reduces wear significantly.',
            'insurance_expiry'     => '2026-11-20',
            'registration_expiry'  => '2026-12-30',
            'emission_due'         => '2027-01-15',
        ]);
        // Electric vehicle — no fuel logs
        foreach ([
            ['service_type' => 'Battery & Electrical Check',      'service_date' => '2024-06-10', 'mileage_at_service' =>  8800, 'cost' => 3500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Battery health at 94% SOH. All cells within normal range'],
            ['service_type' => 'Brake Fluid Change',              'service_date' => '2024-05-15', 'mileage_at_service' =>  8700, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => 'Replaced with Nissan genuine DOT 3 brake fluid'],
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2025-01-22', 'mileage_at_service' => 14000, 'cost' => 2500,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Regenerative braking reducing wear. Pads at 8mm'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2026-02-28', 'mileage_at_service' => 19200, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $leaf->id]));

        foreach ([
            ['trip_date' => '2026-03-05', 'start_km' => 28100, 'end_km' => 28250, 'purpose' => 'personal',  'notes' => 'Zero emissions city drive'],
            ['trip_date' => '2026-03-30', 'start_km' => 28300, 'end_km' => 28420, 'purpose' => 'business',  'notes' => 'Office commute for the week'],
            ['trip_date' => '2026-04-25', 'start_km' => 28480, 'end_km' => 28595, 'purpose' => 'personal',  'notes' => null],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $leaf->id]));

        // ── Vehicle 7: Honda Fit (2017 · 63,800 km) ──────────────────────────
        $fit = Vehicle::create([
            'user_id'              => $dilani->id,
            'make'                 => 'Honda',
            'model'                => 'Fit',
            'year'                 => 2017,
            'mileage'              => 63800,
            'vin'                  => 'MRHGK6750HJ123456',
            'license_plate'        => 'CP LA-8832',
            'color'                => 'Silver',
            'fuel_type'            => 'petrol',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/p6tziTwEbpndWbZDrsMRF3A6YlrqdVp2iHmnj9Y0.jpg',
            'notes'                => 'Used for daily commute and outstation trips. Reliable and fuel efficient. Registration renewal due soon.',
            'insurance_expiry'     => '2026-08-15',
            'registration_expiry'  => '2026-05-25',
            'emission_due'         => '2026-09-30',
        ]);
        foreach ([
            ['date' => '2025-09-25', 'liters' => 36.0, 'cost' => 11880, 'km_reading' => 62500, 'km_per_liter' => 15.3, 'fuel_station' => 'Ceypetco Kandy',       'notes' => null],
            ['date' => '2025-11-30', 'liters' => 33.5, 'cost' => 11055, 'km_reading' => 63100, 'km_per_liter' => 15.9, 'fuel_station' => 'Lanka IOC Peradeniya', 'notes' => null],
            ['date' => '2026-02-15', 'liters' => 38.0, 'cost' => 12540, 'km_reading' => 63600, 'km_per_liter' => 15.6, 'fuel_station' => 'Ceypetco Kandy',       'notes' => 'Full tank'],
            ['date' => '2026-04-08', 'liters' => 34.5, 'cost' => 11385, 'km_reading' => 63750, 'km_per_liter' => 15.8, 'fuel_station' => 'Lanka IOC Kandy',      'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $fit->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-07-12', 'mileage_at_service' => 58600, 'cost' => 4000,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Honda genuine 0W-20 oil'],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2025-10-05', 'mileage_at_service' => 49000, 'cost' => 1800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Spark Plug Replacement',          'service_date' => '2023-05-10', 'mileage_at_service' => 34500, 'cost' => 5800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'repair',      'notes' => 'Replaced all 4 NGK iridium plugs'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2026-03-18', 'mileage_at_service' => 54200, 'cost' => 3200,  'garage_name' => 'AutoCare Maharagama',    'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $fit->id]));

        foreach ([
            ['trip_date' => '2026-02-25', 'start_km' => 63200, 'end_km' => 63420, 'purpose' => 'business',  'notes' => 'Outstation client visit — Colombo to Kandy'],
            ['trip_date' => '2026-03-20', 'start_km' => 63480, 'end_km' => 63650, 'purpose' => 'personal',  'notes' => 'Family day out'],
            ['trip_date' => '2026-04-15', 'start_km' => 63680, 'end_km' => 63790, 'purpose' => 'business',  'notes' => 'Client site visit'],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $fit->id]));

        // ── Vehicle 8: Toyota Vitz (2018 · 52,300 km) ────────────────────────
        $vitz = Vehicle::create([
            'user_id'              => $dilani->id,
            'make'                 => 'Toyota',
            'model'                => 'Vitz',
            'year'                 => 2018,
            'mileage'              => 52300,
            'vin'                  => 'KSP130-0123456',
            'license_plate'        => 'CP MB-4410',
            'color'                => 'Black',
            'fuel_type'            => 'petrol',
            'qr_token'             => Str::uuid(),
            'image'                => 'vehicles/B399wqv32gUMHBm2oyw0EPDpL7kNS5osEHYGkOVf.jpg',
            'notes'                => 'Second vehicle used occasionally for city driving. Well maintained with Toyota genuine parts.',
            'insurance_expiry'     => '2026-12-10',
            'registration_expiry'  => '2027-01-20',
            'emission_due'         => '2027-02-28',
        ]);
        foreach ([
            ['date' => '2025-10-12', 'liters' => 34.0, 'cost' => 11220, 'km_reading' => 51000, 'km_per_liter' => 15.6, 'fuel_station' => 'Ceypetco Kandy',       'notes' => null],
            ['date' => '2025-12-25', 'liters' => 31.5, 'cost' => 10395, 'km_reading' => 51600, 'km_per_liter' => 16.2, 'fuel_station' => 'Lanka IOC Peradeniya', 'notes' => null],
            ['date' => '2026-02-18', 'liters' => 36.0, 'cost' => 11880, 'km_reading' => 52100, 'km_per_liter' => 15.9, 'fuel_station' => 'Ceypetco Kandy',       'notes' => 'Full tank before Colombo trip'],
            ['date' => '2026-04-15', 'liters' => 32.0, 'cost' => 10560, 'km_reading' => 52250, 'km_per_liter' => 16.0, 'fuel_station' => 'Lanka IOC Kandy',      'notes' => null],
        ] as $log) FuelLog::create(array_merge($log, ['vehicle_id' => $vitz->id]));

        foreach ([
            ['service_type' => 'Engine Oil & Filter Change',      'service_date' => '2025-06-20', 'mileage_at_service' => 47100, 'cost' => 3800,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => 'Toyota genuine 5W-30 oil'],
            ['service_type' => 'Brake Pad & Disc Inspection',     'service_date' => '2024-09-14', 'mileage_at_service' => 37500, 'cost' => 2800,  'garage_name' => 'AutoCare Maharagama',    'type' => 'inspection',  'notes' => 'Front pads at 4mm, replace soon'],
            ['service_type' => 'Air Filter Replacement',          'service_date' => '2025-10-28', 'mileage_at_service' => 38000, 'cost' => 1700,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2026-04-15', 'mileage_at_service' => 43000, 'cost' => 3000,  'garage_name' => 'Speedy Motors Nugegoda', 'type' => 'maintenance', 'notes' => null],
        ] as $log) ServiceLog::create(array_merge($log, ['vehicle_id' => $vitz->id]));

        foreach ([
            ['trip_date' => '2026-03-15', 'start_km' => 51800, 'end_km' => 51960, 'purpose' => 'personal',  'notes' => 'Weekend drive'],
            ['trip_date' => '2026-04-05', 'start_km' => 52000, 'end_km' => 52120, 'purpose' => 'personal',  'notes' => 'City errands'],
            ['trip_date' => '2026-05-01', 'start_km' => 52200, 'end_km' => 52290, 'purpose' => 'personal',  'notes' => null],
        ] as $trip) TripLog::create(array_merge($trip, ['vehicle_id' => $vitz->id]));

        // ─── BOOKINGS ─────────────────────────────────────────────────────────
        $b1 = Booking::create([
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
        Booking::create([
            'vehicle_id'   => $vezel->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-05-20',
            'booking_time' => '10:30:00',
            'service_type' => 'AC Service & Diagnostic Check',
            'notes'        => 'AC is not cooling properly. Please run full diagnostic.',
            'status'       => 'confirmed',
        ]);
        Booking::create([
            'vehicle_id'   => $swift->id,
            'garage_id'    => $speedyGarage->id,
            'booking_date' => '2026-05-10',
            'booking_time' => '08:30:00',
            'service_type' => 'Engine Oil Change & Tyre Rotation',
            'notes'        => 'Due for regular service. Please check brake fluid level too.',
            'status'       => 'pending',
        ]);
        Booking::create([
            'vehicle_id'   => $prius->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-05-28',
            'booking_time' => '09:30:00',
            'service_type' => 'Engine Oil Change & Hybrid System Check',
            'notes'        => 'Overdue for oil change. Also check hybrid battery cooling fan.',
            'status'       => 'confirmed',
        ]);
        Booking::create([
            'vehicle_id'   => $leaf->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-05-18',
            'booking_time' => '11:00:00',
            'service_type' => 'Battery Health Check & Brake Fluid Change',
            'notes'        => 'Check battery SOH and replace brake fluid.',
            'status'       => 'pending',
        ]);
        $b6 = Booking::create([
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
        Booking::create([
            'vehicle_id'   => $demio->id,
            'garage_id'    => $autocareGarage->id,
            'booking_date' => '2026-02-10',
            'booking_time' => '10:00:00',
            'service_type' => 'Full Service & Wheel Alignment',
            'notes'        => 'Need full service and alignment checked.',
            'status'       => 'cancelled',
        ]);
        Booking::create([
            'vehicle_id'   => $vitz->id,
            'garage_id'    => $speedyGarage->id,
            'booking_date' => '2026-01-20',
            'booking_time' => '09:00:00',
            'service_type' => 'Engine Oil Change & Brake Pad Inspection',
            'notes'        => 'Overdue oil change and brake pads need checking.',
            'status'       => 'cancelled',
        ]);

        // ─── RATINGS ──────────────────────────────────────────────────────────
        Rating::create([
            'booking_id' => $b1->id,
            'user_id'    => $kavindu->id,
            'garage_id'  => $speedyGarage->id,
            'rating'     => 5,
            'review'     => 'Excellent service! The full service and oil change was done quickly and professionally. The team at Speedy Motors is very knowledgeable and friendly. Highly recommended!',
        ]);
        Rating::create([
            'booking_id' => $b6->id,
            'user_id'    => $dilani->id,
            'garage_id'  => $speedyGarage->id,
            'rating'     => 4,
            'review'     => 'Good service overall. Oil change and air filter replacement done well. Friendly staff and clean workshop. Slightly long wait time but quality work.',
        ]);
    }
}
