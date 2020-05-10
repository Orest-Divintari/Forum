<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;
    /** @test */
    public function a_reply_has_an_owner()
    {
        $this->signIn();
        $reply = factory(Reply::class)->create();
        $this->assertInstanceOf(User::class, $reply->creator);
    }

    /** @test */
    public function a_reply_belongs_to_a_thread()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->assertInstanceOf(Thread::class, $reply->thread);
    }

    /** @test */
    public function a_reply_has_activities()
    {
        $user = $this->signIn();
        $reply = create('App\Reply', ['user_id' => $user->id])->first();

        $this->assertCount(1, $reply->activity);
    }

    /** @test */
    public function a_reply_has_a_path()
    {
        $reply = create('App\Reply');
        $this->assertEquals("/threads/{$reply->thread->channel->slug}/{$reply->thread->slug}#reply-{$reply->id}", $reply->path());
    }

    /** @test */
    public function a_reply_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');
        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subDay();
        $this->assertFalse($reply->wasJustPublished());

    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @Jane-Doe.',
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );
    }

}