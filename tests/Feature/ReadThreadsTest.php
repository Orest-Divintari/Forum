<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Facades\Tests\Setup\ThreadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_fetch_all_replies_for_a_given_thread()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
        $secondReply = create('App\Reply', ['thread_id' => $this->thread->id]);
        $response = $this->get($this->thread->path() . '/replies')->json();
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $threadInChannel = ThreadFactory::withChannel('testChannel')->create();
        $threadNotInChannel = factory(Thread::class)->create();
        $this->get('/threads/' . $threadInChannel->channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->withoutExceptionHandling();
        $this->signIn(create('App\User', ['name' => 'Uric']));
        $threadByUric = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByUric = create('App\Thread');
        $this->get('/threads?by=Uric')
            ->assertSee($threadByUric->title)
            ->assertDontSee($threadNotByUric->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // given that we have 3 threads
        // thread 1 has 2 replies
        // thread 2 has 3 replies
        // thread 3 has 0 replies

        $threadWithTwoReplies = ThreadFactory::withReplies(2)->create();
        $threadWithThreeReplies = ThreadFactory::withReplies(3)->create();
        $threadWithZeroReplies = ThreadFactory::withReplies(0)->create();

        // make a json get request and set popularity to 1

        $response = $this->getJson('threads?popular=1')->json();
        $threads = array_column($response, 'replies_count');
        // because of the setUp function, a 4th thread is created which is finally popped out
        array_pop($threads);
        //  make sure that the order of the returned threads is 3,2,0 ( the indices )
        $this->assertEquals([3, 2, 0], $threads);
    }
}