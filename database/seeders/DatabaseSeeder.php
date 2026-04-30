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

        // Aqua — Service Logs
        $aquaServiceLogs = [
            ['service_type' => 'Oil & Filter Change',            'service_date' => '2024-09-15', 'mileage_at_service' => 55000, 'cost' => 4500,  'garage_name' => 'Speedy Motors Nugegoda',  'type' => 'maintenance', 'notes' => 'Used 5W-30 fully synthetic oil'],
            ['service_type' => 'Full Service & Brake Inspection', 'service_date' => '2024-12-20', 'mileage_at_service' => 57500, 'cost' => 12800, 'garage_name' => 'AutoCare Maharagama',     'type' => 'maintenance', 'notes' => 'Brake pads 60% remaining, front discs good'],
            ['service_type' => 'Tyre Rotation & Wheel Alignment', 'service_date' => '2025-02-10', 'mileage_at_service' => 58000, 'cost' => 3200,  'garage_name' => 'Speedy Motors Nugegoda',  'type' => 'maintenance', 'notes' => null],
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

        // Swift — Service Logs
        $swiftServiceLogs = [
            ['service_type' => 'Engine Oil Change',                      'service_date' => '2024-10-05', 'mileage_at_service' => 38500, 'cost' => 3800, 'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'maintenance', 'notes' => 'Used Suzuki genuine oil'],
            ['service_type' => 'Air Filter & Spark Plug Replacement',    'service_date' => '2025-01-20', 'mileage_at_service' => 40800, 'cost' => 5600, 'garage_name' => 'Suzuki Authorized Service Kandy', 'type' => 'repair',      'notes' => 'Replaced all 4 spark plugs and air filter'],
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
