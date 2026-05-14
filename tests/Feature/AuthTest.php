<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function registration_page_loads()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_register()
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_login()
    {
        $user = User::factory()->create([
            'email'    => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
