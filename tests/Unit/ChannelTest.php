<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function a_channel_conists_of_threads()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $channel = create('App\Channel')->first();
        $thread = create('App\Thread', ['channel_id' => $channel->id, 'user_id' => $user->id])->first();
        $this->assertTrue($channel->threads->contains($thread));

    }

}