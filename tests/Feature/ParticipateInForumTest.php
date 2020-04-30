<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make(['user_id' => auth()->id()]);
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect($thread->path());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }
    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post("{$thread->path()}/replies", [])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function guests_may_not_post_replies()
    {
        $thread = factory(Thread::class)->create();
        $this->post($thread->path() . '/replies', [])
            ->assertRedirect('login');
    }

    /** @test */
    public function authorized_users_can_delete_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
            'body' => $reply->body,
            'user_id' => $reply->creator->id,
        ]);
    }

    /** @test */
    public function unathorized_users_cannot_delete_replies()
    {

        $reply = create('App\Reply');
        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('login');

        $this->signIn();
        $this->delete("/replies/{$reply->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('replies', [
            'body' => $reply->body,
            'id' => $reply->id,
            'user_id' => $reply->creator->id,
        ]);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->put('/replies/' . $reply->id, ['body' => "new body"]);
        $this->assertDatabaseHas('replies', [
            'body' => 'new body',
            'id' => $reply->id,
            'user_id' => auth()->id(),
        ]);
    }

    /** @test */
    public function unathorized_users_cannot_update_replies()
    {

        $reply = create('App\Reply');

        $this->put('/replies/' . $reply->id, ['body' => "new body"])
            ->assertRedirect('login');

        $this->signIn();

        $this->put('/replies/' . $reply->id, ['body' => "new body"])
            ->assertStatus(403);

        $this->assertDatabaseMissing('replies', [
            'body' => 'new body',
            'id' => $reply->id,
            'user_id' => auth()->id(),
        ]);
    }
}