<?php

namespace Tests\Feature;

use App\Reply;
use Facades\Tests\Setup\ThreadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function the_creator_of_a_thead_may_mark_a_reply_as_the_best_rep()
    {

        $this->signIn();
        $thread = ThreadFactory::withReplies(2)->ownedBy($this->signIn())->create();
        $reply = Reply::latest('id')->first();
        $this->json('post', route('best-replies.store', $reply->id));
        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    public function only_the_creator_of_a_thread_can_mark_a_reply_as_the_best()
    {
        $this->signIn();
        $thread = ThreadFactory::withReplies(2)->create();
        $reply = Reply::latest('id')->first();
        $this->json('post', route('best-replies.store', $reply->id))
            ->assertStatus(403);
        $this->assertFalse($reply->fresh()->isBest());

    }
}