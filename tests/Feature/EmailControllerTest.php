<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_user_emails()
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.emails.index', $user));
        $response->assertOk()->assertJsonFragment(['email' => $email->email]);
    }

    public function test_store_creates_email_for_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = ['email' => 'test@example.com'];
        $response = $this->postJson(route('api.emails.store', $user), $payload);

        $response->assertCreated()->assertJsonFragment(['email' => 'test@example.com']);
        $this->assertDatabaseHas('emails', ['email' => 'test@example.com', 'user_id' => $user->id]);
    }

    public function test_show_returns_specific_email()
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.emails.show', [$user, $email]));
        $response->assertOk()->assertJsonFragment(['email' => $email->email]);
    }

    public function test_update_modifies_email()
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $payload = ['email' => 'updated@example.com'];
        $response = $this->putJson(route('api.emails.update', [$user, $email]), $payload);

        $response->assertOk()->assertJsonFragment(['email' => 'updated@example.com']);
        $this->assertDatabaseHas('emails', ['id' => $email->id, 'email' => 'updated@example.com']);
    }

    public function test_destroy_deletes_email()
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('api.emails.destroy', [$user, $email]));

        $response->assertOk()->assertJson(['message' => 'Email deleted successfully']);
        $this->assertDatabaseMissing('emails', ['id' => $email->id]);
    }

    public function test_cannot_access_other_users_email()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $otherUser->id]);
        Sanctum::actingAs($user);

        $this->getJson(route('api.emails.show', [$user, $email]))->assertStatus(404);
        $this->putJson(route('api.emails.update', [$user, $email]), ['email' => 'fail@example.com'])->assertStatus(404);
        $this->deleteJson(route('api.emails.destroy', [$user, $email]))->assertStatus(404);
    }
}
