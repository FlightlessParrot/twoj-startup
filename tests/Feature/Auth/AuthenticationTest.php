<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.login'), [
            'phone' => $user->phone,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token', 'user']);
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('api.login'), [
            'phone' => $user->phone,
            'password' => 'wrong-password',
        ])->assertInvalid(['phone']);
    }

    public function test_users_can_not_authenticate_with_invalid_phone(): void
    {
        $this->postJson(route('api.login'), [
            'phone' => 'invalid-phone',
            'password' => 'password',
        ])->assertInvalid(['phone']);
    }
    
    #[Depends('test_users_can_authenticate')]
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        // Simulate login to get a token
        $loginResponse = $this->postJson(route('api.login'), [
            'phone' => $user->phone,
            'password' => 'password',
        ]);
        $token = $loginResponse->json('token');
        
        // Use the token to authenticate the logout request
        $response = $this->withToken($token)
            ->postJson(route('api.logout'));

        $response->assertOk();
        $response->assertJson(['message' => 'Logged out successfully']);
    }
}
