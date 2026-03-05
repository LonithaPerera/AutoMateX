<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'make'          => $this->faker->randomElement(['Toyota', 'Honda', 'Suzuki']),
            'model'         => $this->faker->randomElement(['Premio', 'Fit', 'Alto']),
            'year'          => $this->faker->numberBetween(2010, 2023),
            'mileage'       => $this->faker->numberBetween(10000, 80000),
            'vin'           => $this->faker->unique()->regexify('[A-Z0-9]{17}'),
            'license_plate' => $this->faker->regexify('[A-Z]{3}-[0-9]{4}'),
            'color'         => $this->faker->colorName(),
            'fuel_type'     => $this->faker->randomElement(['petrol', 'diesel', 'hybrid']),
        ];
    }
}