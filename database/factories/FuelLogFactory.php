<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuelLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vehicle_id'   => Vehicle::factory(),
            'date'         => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'liters'       => $this->faker->randomFloat(1, 20, 55),
            'cost'         => $this->faker->numberBetween(3000, 15000),
            'km_reading'   => $this->faker->numberBetween(20000, 80000),
            'km_per_liter' => $this->faker->randomFloat(2, 8, 18),
            'fuel_station' => $this->faker->randomElement(['CPC Nugegoda', 'Lanka IOC Maharagama', 'Ceypetco Kandy']),
            'notes'        => null,
        ];
    }
}
