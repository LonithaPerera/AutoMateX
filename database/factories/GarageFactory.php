<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GarageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'name'           => $this->faker->company() . ' Garage',
            'address'        => $this->faker->streetAddress(),
            'city'           => $this->faker->randomElement(['Colombo', 'Nugegoda', 'Kandy', 'Galle']),
            'phone'          => '07' . $this->faker->numerify('########'),
            'description'    => $this->faker->sentence(),
            'specialization' => $this->faker->randomElement(['Toyota', 'Honda', 'General']),
            'is_active'      => true,
        ];
    }
}