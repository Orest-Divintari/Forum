<?php

namespace Tests\Unit;

use App\Trending\TrendingThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Redis::del('trending_threads');
        $this->trending = app(TrendingThread::class);
    }

    /** @test */
    public function the_score_of_a_thread_can_be_incremented()
    {
        $thread = create('App\Thread');

        $this->assertCount(0, Redis::zrevrange('trending_threads', 0, -1));

        $this->trending->incrementScore($thread);

        $this->assertCount(1, Redis::zrevrange('trending_threads', 0, -1));

    }

    /** @test */
    public function it_can_fetch_the_top_popular_threads()
    {

        // generate 10 threads where the score for each one will be the thread->idate
        // first thread will have score 1
        // last thread will have score 10
        $threads = createMany('App\Thread', $times = 10);
        $threads->each(function ($thread) {
            $times = $thread->id;
            $this->trending->incrementScore($thread, $times);
        });

        // reverse the order of the threds and slice 5 -> top 5 popular threads
        // the first item will have score 10
        // the last item will have score 6

        $threads = $threads->reverse()->take(5);

        // get the top 5 popular threads
        $popular_threads = collect($this->trending->popular(5));

        $this->assertEquals($threads[9]->title, $popular_threads[0]->title);
        $this->assertEquals($threads[8]->title, $popular_threads[1]->title);
        $this->assertEquals($threads[7]->title, $popular_threads[2]->title);
        $this->assertEquals($threads[6]->title, $popular_threads[3]->title);
        $this->assertEquals($threads[5]->title, $popular_threads[4]->title);
    }

}