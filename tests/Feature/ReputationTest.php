<?php

namespace Tests\Feature;

use App\Reputation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReputationTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function a_user_earns_points_when_creates_a_thread()
    {
        $thread = create('App\Thread');
        $this->assertEquals(10, $thread->creator->reputation);

    }

    /** @test */
    public function a_user_loses_points_when_deletes_a_thread()
    {

        $user = $this->signIn();
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->assertEquals(Reputation::THREAD_CREATED, $thread->creator->reputation);

        $this->delete($thread->path());

        $this->assertEquals(0, $user->fresh()->reputation);

    }

    /** @test */
    public function a_user_earns_points_replies_to_a_thread()
    {
        $thread = create('App\Thread');
        $reply = $thread->addReply([
            'body' => 'some reply',
            'user_id' => create('App\User')->id,
        ]);
        $this->assertEquals(Reputation::REPLY_POSTED, $reply->creator->reputation);

    }

    /** @test */
    public function a_user_loses_points_when_deletes_a_reply()
    {
        $user = $this->signIn();

        $reply = create('App\Reply', ['user_id' => $user->id]);
        $this->assertEquals(Reputation::REPLY_POSTED, $reply->fresh()->creator->reputation);
        $this->delete(route('replies.delete', $reply->id));

        $this->assertEquals(0, $user->fresh()->reputation);

    }

    /** @test */
    public function a_user_earns_points_when_their_Reply_is_marked_as_best()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->post(route('best-replies.store', $reply->id));
        $total = Reputation::REPLY_POSTED + Reputation::BEST_REPLY;
        $this->assertEquals($total, $reply->fresh()->creator->reputation);

    }

    /** @test */
    public function a_user_loses_points_when_their_reply_is_unmarked_from_best_reply()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $firstBestReply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->post(route('best-replies.store', $firstBestReply->id));
        $total = Reputation::REPLY_POSTED + Reputation::BEST_REPLY;
        $this->assertEquals($total, $firstBestReply->fresh()->creator->reputation);

        // mark another reply as the best, consequently the previous reply is not the best anymore
        $anotherBestReply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->post(route('best-replies.store', $anotherBestReply->id));

        $this->assertEquals($total, $anotherBestReply->fresh()->creator->reputation);
        $this->assertEquals($total - Reputation::BEST_REPLY, $firstBestReply->creator->reputation);

    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_favorited()
    {
        $reply = create('App\Reply');
        $this->assertEquals(Reputation::REPLY_POSTED, $reply->creator->reputation);

        $this->signIn();
        $this->post(route('replies.favorite', $reply->id));

        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;
        $this->assertEquals($total, $reply->creator->fresh()->reputation);

    }

    /** @test */
    public function a_user_loses_points_when_their_reply_is_unfavorited()
    {
        $reply = create('App\Reply');
        $this->assertEquals(Reputation::REPLY_POSTED, $reply->creator->reputation);

        $this->signIn();
        $this->post(route('replies.favorite', $reply->id));

        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;
        $this->assertEquals($total, $reply->creator->fresh()->reputation);

        $this->delete(route('replies.unfavorite', $reply->id));
        $this->assertEquals($total - Reputation::REPLY_UNFAVORITED, $reply->creator->fresh()->reputation);

    }
}