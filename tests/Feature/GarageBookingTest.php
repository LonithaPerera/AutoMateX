<?php

namespace Tests\Feature;

use App\Models\Garage;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GarageBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function garages_page_loads()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->get('/garages');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_register_a_garage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/garages', [
            'name'           => 'AutoHub Lanka',
            'address'        => '123 Main Street',
            'city'           => 'Nugegoda',
            'phone'          => '0112345678',
            'description'    => 'Best garage in town',
            'specialization' => 'Toyota Specialist',
        ]);

        $this->assertDatabaseHas('garages', ['name' => 'AutoHub Lanka']);
    }

    /** @test */
    public function user_can_book_appointment()
    {
        $owner   = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);
        $garage  = Garage::factory()->create();

        $response = $this->actingAs($owner)->post("/garages/{$garage->id}/book", [
            'vehicle_id'   => $vehicle->id,
            'booking_date' => now()->addDays(2)->format('Y-m-d'),
            'booking_time' => '10:00',
            'service_type' => 'Oil Change',
            'notes'        => 'Please check brakes too',
        ]);

        $this->assertDatabaseHas('bookings', [
            'vehicle_id'   => $vehicle->id,
            'garage_id'    => $garage->id,
            'service_type' => 'Oil Change',
        ]);
    }

    /** @test */
    public function user_can_view_their_bookings()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->get('/bookings');
        $response->assertStatus(200);
    }
}