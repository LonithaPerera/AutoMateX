<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceLogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_service_history()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}/service");
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_add_service_record()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/vehicles/{$vehicle->id}/service", [
            'service_type'       => 'Oil Change',
            'service_date'       => '2026-03-05',
            'mileage_at_service' => 44000,
            'cost'               => 3500,
            'garage_name'        => 'AutoHub Lanka',
            'type'               => 'maintenance',
            'notes'              => 'Full synthetic oil',
        ]);

        $this->assertDatabaseHas('service_logs', [
            'service_type' => 'Oil Change',
            'vehicle_id'   => $vehicle->id,
        ]);
    }
}