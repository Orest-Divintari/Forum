<?php

namespace Tests\Unit;

use App\Activity;
use App\Channel;
use App\Thread;
use App\User;
use Facades\Tests\Setup\ThreadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }
    use RefreshDatabase;

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $this->assertEquals($this->thread->path(), "/threads/{$this->thread->channel->slug}/{$this->thread->id}");
    }
    /** @test */
    public function a_thread_has_replies()
    {

        $replyAttributes = [
            'body' => 'test reply',
            'user_id' => $this->thread->creator->id,
        ];
        $reply = $this->thread->addReply($replyAttributes);
        $this->assertCount(1, $this->thread->replies);
        $this->assertTrue($this->thread->replies->contains($reply));
    }

    // /** @test */
    // public function a_thread_has_a_path(){
    //     $this->assertEquals($this->thread->path(), '/threads/' . $this->thread->id);
    // }
    /** @test */
    public function a_thread_has_an_owner()
    {
        $this->withoutExceptionHandling();
        $this->assertInstanceOf(User::class, $this->thread->creator);

    }
    /** @test */
    public function a_thread_can_add_a_reply()
    {

        $replyAttributes = [
            'body' => 'test reply',
            'user_id' => 1,
        ];

        $this->thread->addReply($replyAttributes);
        $this->assertCount(1, $this->thread->replies);

    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {

        $thread = create('App\Thread');
        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function a_thread_has_activities()
    {
        $user = $this->signIn();
        // $this->post('/threads', $thread);
        $thread = ThreadFactory::ownedBy($this->signIn())->create();
        $this->assertCount(1, $thread->activity);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to_by_a_user()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();
        $this->assertEquals(1, $thread->subscriptions()->where(['user_id' => auth()->id()])->count());

    }

    /** @test */
    public function a_thread_can_be_unsubscribe_from()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->assertCount(1, $thread->fresh()->subscriptions);
        $thread->unsubscribe();
        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_whether_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribedTo);
    }

}