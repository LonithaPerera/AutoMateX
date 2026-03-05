<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::factory()->create(['role' => 'vehicle_owner']);
    }

    /** @test */
    public function user_can_view_vehicles_page()
    {
        $user = $this->makeUser();
        $response = $this->actingAs($user)->get('/vehicles');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_add_a_vehicle()
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/vehicles', [
            'make'          => 'Toyota',
            'model'         => 'Premio',
            'year'          => 2018,
            'mileage'       => 44000,
            'vin'           => 'JT2AE09W6H1234567',
            'license_plate' => 'CAY-8485',
            'color'         => 'Pearl White',
            'fuel_type'     => 'petrol',
        ]);

        $this->assertDatabaseHas('vehicles', [
            'make'  => 'Toyota',
            'model' => 'Premio',
        ]);

        $response->assertRedirect('/vehicles');
    }

    /** @test */
    public function user_can_view_vehicle_details()
    {
        $user    = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_delete_a_vehicle()
    {
        $user    = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/vehicles/{$vehicle->id}");

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
        $response->assertRedirect('/vehicles');
    }

    /** @test */
    public function guest_cannot_add_vehicle()
    {
        $response = $this->post('/vehicles', [
            'make'  => 'Toyota',
            'model' => 'Premio',
        ]);
        $response->assertRedirect('/login');
    }
}