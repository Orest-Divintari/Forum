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
    public function a_user_may_have_subscriptions()
    {
        $user = $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe($user->id);
        $this->assertCount(1, $user->subscriptions);

    }
}