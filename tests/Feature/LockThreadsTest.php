<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_locked_thread_may_not_receive_new_replies()
    {
        // given that we have a user who is admin
        // given that we have a thread that is locked
        // users should not be able to add new replies to that thread

        $user = $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'foo body',
            'user_id' => $user->id,
        ])->assertStatus(422);

    }

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        // $this->withoutExceptionHandling();

        $user = $this->signIn();
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);

    }

    /** @test */
    public function admins_can_lock_threads()
    {
        $user = factory('App\User')->state('administrator')->create();

        $this->signIn($user);

        $thread = create('App\Thread');

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

}