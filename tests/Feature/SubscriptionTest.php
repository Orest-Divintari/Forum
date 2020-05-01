<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscribe');
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
        ]);
        $this->assertCount(1, $thread->subscribers);

    }

    /** @test */
    public function guests_cannot_subscribe_to_a_thread()
    {
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscribe')
            ->assertRedirect('login');
    }

}