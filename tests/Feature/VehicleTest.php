<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::factory()->create(['role' => 'vehicle_owner']);
    }

    #[Test]
    public function user_can_view_vehicles_page()
    {
        $user = $this->makeUser();
        $response = $this->actingAs($user)->get('/vehicles');
        $response->assertStatus(200);
    }

    #[Test]
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

    #[Test]
    public function user_can_view_vehicle_details()
    {
        $user    = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}");
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_delete_a_vehicle()
    {
        $user    = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/vehicles/{$vehicle->id}");

        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
        $response->assertRedirect('/vehicles');
    }

    #[Test]
    public function guest_cannot_add_vehicle()
    {
        $response = $this->post('/vehicles', [
            'make'  => 'Toyota',
            'model' => 'Premio',
        ]);
        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_can_archive_and_restore_vehicle()
    {
        $user    = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        // Archive
        $this->actingAs($user)->delete("/vehicles/{$vehicle->id}");
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);

        // Restore
        $response = $this->actingAs($user)->patch("/vehicles/{$vehicle->id}/restore");
        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'deleted_at' => null]);
        $response->assertRedirect();
    }

    #[Test]
    public function user_can_view_archived_vehicles()
    {
        $user    = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user)->delete("/vehicles/{$vehicle->id}");

        $response = $this->actingAs($user)->get('/vehicles/archived');
        $response->assertStatus(200);
    }

    #[Test]
    public function user_cannot_access_another_users_vehicle()
    {
        $owner   = $this->makeUser();
        $other   = $this->makeUser();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->get("/vehicles/{$vehicle->id}");
        $response->assertStatus(403);
    }
}
