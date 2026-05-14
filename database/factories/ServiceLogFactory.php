<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vehicle_id'         => Vehicle::factory(),
            'service_type'       => $this->faker->randomElement(['Oil Change', 'Brake Inspection', 'Tyre Rotation', 'Full Service']),
            'service_date'       => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'mileage_at_service' => $this->faker->numberBetween(20000, 80000),
            'cost'               => $this->faker->numberBetween(2000, 20000),
            'garage_name'        => $this->faker->randomElement(['Speedy Motors', 'AutoCare Lanka', 'AutoHub Lanka']),
            'type'               => $this->faker->randomElement(['maintenance', 'repair', 'inspection']),
            'notes'              => null,
        ];
    }
}
