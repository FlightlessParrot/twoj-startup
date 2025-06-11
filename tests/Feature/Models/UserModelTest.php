<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_it_can_get_user(): void
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $email->user);
        $this->assertEquals($user->id, $email->user->id);
    }
}
