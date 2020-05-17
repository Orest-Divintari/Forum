<?php

namespace Tests\Feature;

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
    public function a_user_earns_points_replies_to_a_thread()
    {
        $thread = create('App\Thread');
        $reply = $thread->addReply([
            'body' => 'some reply',
            'user_id' => create('App\User')->id,
        ]);
        $this->assertEquals(2, $reply->creator->reputation);

    }

    /** @test */
    public function a_user_earns_points_when_their_Reply_is_marked_as_best()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply');
        $thread->markBestReply($reply);
        $this->assertEquals(52, $reply->creator->reputation);

    }

}