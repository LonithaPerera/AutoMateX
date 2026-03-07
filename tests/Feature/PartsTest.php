<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PartsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function parts_page_loads()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->get('/parts');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_search_parts_by_make()
    {
        $user = User::factory()->create();

        DB::table('parts')->insert([
            'vehicle_make'      => 'Toyota',
            'vehicle_model'     => 'Premio',
            'vehicle_year_from' => 2007,
            'vehicle_year_to'   => 2021,
            'part_name'         => 'Oil Filter',
            'part_category'     => 'Filters',
            'oem_part_number'   => '90915-03003',
            'brand'             => 'Toyota Genuine',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $response = $this->actingAs($user)->get('/parts?make=Toyota');
        $response->assertStatus(200);
        $response->assertSee('Oil Filter');
        $response->assertSee('90915-03003');
    }

    /** @test */
    public function search_returns_no_results_for_unknown_make()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->get('/parts?make=UnknownBrand');
        $response->assertStatus(200);
        $response->assertSee('0');
    }
}