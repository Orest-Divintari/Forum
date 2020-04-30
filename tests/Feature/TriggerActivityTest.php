<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Thread;
use App\Activity;
use Facades\Tests\Setup\ThreadFactory;

class TriggerActivityTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    /** @test */
    public function creating_a_thread_records_activity()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $thread = ThreadFactory::ownedBy($user)->create();

        $this->assertDatabaseHas('activities', [
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread',
            'type' => 'created_thread',
            'user_id' => $user->id
        ]);
        $this->assertCount(1, $thread->activity);
        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function creating_a_reply_records_activity()
    {
        $user = $this->signIn();
        $reply = create('App\Reply', ['user_id' => $user->id]);

        // 2 because the first activity is for the created thread 
        // so this will be the second activity
        $activity = Activity::find(2);
        $this->assertEquals($reply->id, $activity->subject->id);
        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'subject_type' => 'App\Reply',
            'subject_id' => $reply->id,
            'type' => 'created_reply'
        ]);
        $this->assertCount(1, $reply->activity);
    }

    /** @test */
    public function favoriting_a_reply_records_activity()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $reply->favorite(auth()->id());
        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'subject_type' => 'App\Favorite',
            'subject_id' => $reply->favorites->first()->id,
            'type' => 'created_favorite'
        ]);
        $this->assertCount(1, $reply->favorites);
    }
}