<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    use RefreshDatabase;
    /** @test */
    public function a_user_may_have_activities()
    {
        $user = $this->signIn();
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->assertCount(1, $user->activity);
    }

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply', ['user_id' => $user->id]);
        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function a_user_can_determine_the_path_to_his_avatar()
    {
        $user = create('App\User', ['avatar_path' => 'avatars/avatar.jpg']);

        $this->assertEquals(asset('/avatars/avatar.jpg'), $user->avatar_path);
    }
}