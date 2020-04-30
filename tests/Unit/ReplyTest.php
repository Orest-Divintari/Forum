<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\User;
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
        $this->assertEquals("/threads/{$reply->thread->channel->slug}/{$reply->thread->id}#reply-{$reply->id}", $reply->path());
    }
}