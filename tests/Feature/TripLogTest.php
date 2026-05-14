<?php

namespace Tests\Feature;

use App\Models\TripLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TripLogTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_trip_log()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}/trips");
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_log_a_trip()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id, 'mileage' => 30000]);

        $response = $this->actingAs($user)->post("/vehicles/{$vehicle->id}/trips", [
            'trip_date' => '2026-04-10',
            'start_km'  => 30000,
            'end_km'    => 30150,
            'purpose'   => 'business',
            'notes'     => 'Client visit',
        ]);

        $this->assertDatabaseHas('trip_logs', [
            'vehicle_id' => $vehicle->id,
            'start_km'   => 30000,
            'end_km'     => 30150,
            'purpose'    => 'business',
        ]);

        $response->assertRedirect();
    }

    #[Test]
    public function user_can_delete_a_trip()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $trip    = TripLog::create([
            'vehicle_id' => $vehicle->id,
            'trip_date'  => '2026-04-10',
            'start_km'   => 30000,
            'end_km'     => 30150,
            'purpose'    => 'personal',
        ]);

        $response = $this->actingAs($user)->delete("/vehicles/{$vehicle->id}/trips/{$trip->id}");

        $this->assertDatabaseMissing('trip_logs', ['id' => $trip->id]);
        $response->assertRedirect();
    }

    #[Test]
    public function trip_requires_valid_purpose()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/vehicles/{$vehicle->id}/trips", [
            'trip_date' => '2026-04-10',
            'start_km'  => 30000,
            'end_km'    => 30150,
            'purpose'   => 'invalid_purpose',
        ]);

        $response->assertSessionHasErrors('purpose');
    }

    #[Test]
    public function user_can_download_trip_pdf()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}/trips/pdf");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
