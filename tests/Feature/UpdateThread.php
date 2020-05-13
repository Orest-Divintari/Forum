<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
        $this->thread = create('App\Thread', ['user_id' => auth()->id()]);
    }

    /** @test */
    public function authorized_user_may_update_a_thread()
    {

        $channel = create('App\Channel');
        $this->patch($this->thread->path(), [
            'title' => 'changed title',
            'body' => 'changed body',
            'channel_id' => $channel->id,
            'g-recaptcha-response' => 'some token',
        ]);

        tap($this->thread->fresh(), function ($thread) use ($channel) {
            $this->assertEquals('changed title', $thread->title);
            $this->assertEquals('changed body', $thread->body);
            $this->assertEquals($channel->id, $thread->channel_id);
        });

    }

    /** @test */
    public function unathorized_users_may_not_update_a_thread()
    {

        $thread = create('App\Thread');

        $this->patch($thread->path(), [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_thread_requires_a_body_when_updated()
    {

        $channel = create('App\Channel');
        $this->patch($this->thread->path(), [
            'title' => 'changed title',
            'channel_id' => $channel->id,
            'g-recaptcha-response' => 'some token',
        ])->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_thread_requires_a_title_when_updated()
    {

        $channel = create('App\Channel');
        $this->patch($this->thread->path(), [
            'body' => 'changed body',
            'channel_id' => $channel->id,
            'g-recaptcha-response' => 'some token',
        ])->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_thread_requires_a_channel_id_when_updated()
    {

        $channel = create('App\Channel');
        $this->patch($this->thread->path(), [
            'body' => 'changed body',
            'title' => 'changed title',
            'g-recaptcha-response' => 'some token',
        ])->assertSessionHasErrors('channel_id');

    }

}