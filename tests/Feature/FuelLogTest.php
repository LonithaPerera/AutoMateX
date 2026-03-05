<?php

namespace Tests\Feature;

use App\Models\FuelLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FuelLogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_fuel_logs()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}/fuel");
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_add_fuel_log()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id, 'mileage' => 40000]);

        $response = $this->actingAs($user)->post("/vehicles/{$vehicle->id}/fuel", [
            'date'         => '2026-03-05',
            'liters'       => 40,
            'cost'         => 8000,
            'km_reading'   => 40400,
            'fuel_station' => 'CPC Nugegoda',
            'notes'        => 'Full tank',
        ]);

        $this->assertDatabaseHas('fuel_logs', [
            'vehicle_id' => $vehicle->id,
            'liters'     => 40,
        ]);
    }
}