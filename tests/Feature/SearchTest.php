<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    /** @test */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        create('App\Thread', []);
        create('App\Thread', []);

        $search = 'foobar';

        create('App\Thread', ['body' => "a thread with the {$search} term"]);
        create('App\Thread', ['body' => "a thread with the {$search} term"]);

        do {
            $results = $this->getJson("/threads/search?q={$search}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        // remove the 4 recently created threads from algolia
        Thread::latest()->take(4)->unsearchable();

    }
}