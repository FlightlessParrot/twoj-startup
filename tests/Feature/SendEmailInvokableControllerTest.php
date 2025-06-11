<?php

namespace Tests\Feature;

use App\Mail\WelcomeMessage;
use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SendEmailInvokableControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_welcome_emails_to_all_addresses()
    {
        Mail::fake();

        $user = User::factory()->create(['name' => 'Janek']);
        $emails = [
            Email::factory()->create(['user_id' => $user->id, 'email' => 'one@example.com']),
            Email::factory()->create(['user_id' => $user->id, 'email' => 'two@example.com']),
        ];

        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.users.send-welcome'));

        $response->assertOk()
            ->assertJson(['message' => 'Emails sent successfully']);

        foreach ($emails as $email) {
            Mail::assertSent(WelcomeMessage::class, function ($mail) use ($email, $user) {
                return $mail->hasTo($email->email) && $mail->userName === $user->name;
            });
        }
    }

    public function test_guest_cannot_send_welcome_emails()
    {
        $response = $this->postJson(route('api.users.send-welcome'));
        $response->assertUnauthorized();
    }

    public function test_welcome_message_mailable_content()
    {
        $userName = 'Janek';
        $userEmail = 'janek@example.com';
        $mailable = new WelcomeMessage($userName);


        $mailable->to($userEmail);
        $mailable->assertSeeInText("Witamy użytkownika $userName");
    }
}
