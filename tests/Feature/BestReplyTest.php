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

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_threads_table_should_be_updated_to_reflect_the_deletion()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', [
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
        ]);

        $this->json('post', route('best-replies.store', $reply->id));

        $this->assertTrue($reply->fresh()->isBest());

        $this->delete(route('replies.delete', $reply->id));

        $this->assertNull($thread->best_reply_id);
    }
}