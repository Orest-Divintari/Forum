<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Activity;
use App\User;
use Carbon\Carbon;
class ActivityTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;
    /** @test */
    public function an_activity_belongs_to_a_user()
    {
        $user = $this->signIn();
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $activity = Activity::first();
        $this->assertInstanceOf(User::class, $activity->owner);
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        // given we have a thread
        // and another thread from a week ago
        // when we fetch their feed
        // then it should be returned in a propper format

        $user = $this->signIn();
        create('App\Thread', [
            'user_id' => $user->id
            ]);
        create('App\Thread', [
            'user_id' => $user->id
            
        ]);
        // update the created_at of the activity to 1 week earlier
        // in order to test that the feed function returns the data in the propper format
        // which is group by date
        $user->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);
    
        $activities = Activity::feed($user);
        
        $this->assertCount(2, $activities);

        $this->assertTrue($activities->keys()
            ->contains(Carbon::now()->format('Y-m-d')));

        $this->assertTrue($activities->keys()
            ->contains(Carbon::now()->subWeek()->format('Y-m-d')));
    }
}
