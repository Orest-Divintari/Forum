<?php

namespace Tests\Feature;

use App\Notifications\VerifyEmailQueued;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_verification_email_is_sent_when_a_new_user_is_registered()
    {
        Event::fake();
        $this->post('/register', [
            'name' => 'orest',
            'email' => 'qq@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        Event::assertDispatched(Registered::class);

    }

    /** @test */
    public function a_user_can_fully_has_to_confirm_their_email_addresses()
    {
        $this->post('/register', [
            'name' => 'orest',
            'email' => 'qq@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $user = User::first();

        $this->assertNull($user->email_verified_at);

    }

    /** @test */
    public function the_confirmation_email_is_queued()
    {
        Event::fake();
        Queue::fake();
        Mail::fake();
        Notification::fake();
        $this->post('/register', [
            'name' => 'orest',
            'email' => 'qq@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        Event::assertDispatched(Registered::class);

        Queue::assertPushed(VerifyEmailQueued::class);

    }

}