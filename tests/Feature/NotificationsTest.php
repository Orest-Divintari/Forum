<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();

    }
    /** @test */
    public function a_user_is_prepared_for_a_user_when_a_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        // create n subscribe the auth user to this thread
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // add my own reply
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        // add reply by another user
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply from another user',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        // create n subscribe the auth user to this thread

        create(DatabaseNotification::class);
        $user = auth()->user();

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->fresh()->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->delete("/profiles/" . $user->name . "/notifications/{$notificationId}");

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });

    }

    /** @test */
    public function a_user_can_fetch_his_own_unread_notifications()
    {

        create(DatabaseNotification::class);

        $response = $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json();
        $this->assertCount(1, $response);

    }

}