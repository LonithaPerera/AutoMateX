<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeOwner(): User
    {
        return User::factory()->create(['role' => 'vehicle_owner']);
    }

    #[Test]
    public function admin_can_view_dashboard()
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_users_list()
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_bookings_list()
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/admin/bookings');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_garages_list()
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/admin/garages');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_activity_log()
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/admin/activity');
        $response->assertStatus(200);
    }

    #[Test]
    public function non_admin_cannot_access_admin_dashboard()
    {
        $owner    = $this->makeOwner();
        $response = $this->actingAs($owner)->get('/admin');
        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }
}
