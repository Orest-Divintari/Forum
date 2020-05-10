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
        // make sure to increase the replies count when a new reply is posted on a thread
        $this->assertEquals(1, $thread->fresh()->replies_count);

    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post("{$thread->path()}/replies", ["body" => ''])
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
        $thread = $reply->thread;
        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
            'body' => $reply->body,
            'user_id' => $reply->creator->id,
        ]);
        $this->assertEquals(0, $thread->fresh()->replies_count);
        //make sure that after we delete the reply
        // we also decrement the replies_count on the threads table

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

    /** @test */
    public function replies_that_containt_spam_may_not_be_created()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support',
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);

    }

    /** @test */
    public function user_may_only_reply_a_maximu_of_once_per_minute()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Some reply',
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(302);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }
}