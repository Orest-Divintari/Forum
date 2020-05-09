<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrendingThreadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->trending = app(Trending::class);
        parent::setUp();
        $this->trending->reset();
    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $thread = create('App\Thread');
        $this->assertCount(0, $this->trending->get());
        $this->get($thread->path());
        $trending = $this->trending->get();
        $this->assertCount(1, $trending);
        $this->assertEquals($thread->title, $trending[0]->title);
    }

}