<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
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
    public function a_user_can_subscribe_to_threads()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');
        $this->assertCount(1, $thread->subscriptions);

    }

    /** @test */
    public function guests_cannot_subscribe_to_a_thread()
    {
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');
        $this->assertCount(0, $thread->subscriptions);
    }
}