<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailModelTest extends TestCase
{
    private array $emailData;

    public function setUp(): void
    {
        parent::setUp();
        $this->emailData = [
            'email' => 'test@domain.pl',
        ];
    }
    public function test_it_can_create_email(): void
    {
        $user = User::factory()->create();
        $email = $user->emails()->create($this->emailData);

        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals($this->emailData['email'], $email->email);
        $this->assertDatabaseHas('emails', [
            'id' => $email->id,
            'email' => $this->emailData['email'],
            'user_id' => $user->id,
        ]);
    }

    public function test_it_can_get_related_emails(): void
    {
        $user = User::factory()->create();
        $emails = Email::factory(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->emails);
    }
}
