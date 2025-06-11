<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_users_list()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.users.index'));

        $response->assertOk()
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_show_returns_single_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.users.show', $user));

        $response->assertOk()
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'name' => 'Updated Name',
            'last_name' => 'Updated Last',
            'phone' => '+481234567891',
        ];

        $response = $this->putJson(route('api.users.update', $user), $payload);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Updated Name', 'last_name' => 'Updated Last', 'phone' => '+481234567891']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_update_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);
        Sanctum::actingAs($user);

        $payload = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->putJson(route('api.users.password.update', $user), $payload);

        $response->assertOk()
            ->assertJson(['message' => 'Password updated successfully']);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_destroy_deletes_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('api.users.destroy', $user));

        $response->assertOk()
            ->assertJson(['message' => 'User deleted successfully']);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
