<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuggestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function suggestion_page_loads_for_vehicle()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        MaintenanceSchedule::create([
            'service_name' => 'Oil Change',
            'interval_km'  => 5000,
            'category'     => 'Engine',
            'description'  => 'Change engine oil',
        ]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}/suggestions");
        $response->assertStatus(200);
        $response->assertSee('Oil Change');
    }

    /** @test */
    public function suggestion_shows_overdue_when_past_interval()
    {
        $user    = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id,
            'mileage' => 50000,
        ]);

        MaintenanceSchedule::create([
            'service_name' => 'Oil Change',
            'interval_km'  => 5000,
            'category'     => 'Engine',
            'description'  => 'Change engine oil',
        ]);

        $response = $this->actingAs($user)->get("/vehicles/{$vehicle->id}/suggestions");
        $response->assertStatus(200);
        $response->assertSee('Overdue');
    }
}