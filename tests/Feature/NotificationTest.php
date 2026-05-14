<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_notifications_page()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_mark_all_notifications_read()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->patch('/notifications/read-all');
        $response->assertRedirect();
    }

    #[Test]
    public function guest_cannot_access_notifications()
    {
        $response = $this->get('/notifications');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function unread_count_endpoint_returns_json()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/notifications/count');
        $response->assertStatus(200);
        $response->assertJsonStructure(['count']);
    }
}
