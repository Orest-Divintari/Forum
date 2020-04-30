<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    /** @test */
    public function a_user_has_a_profile()
    {
        $this->withoutExceptionHandling();
        $user = create('App\User');
        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_created_by_the_associated_user()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $thread = create('App\Thread', ['user_id' => $user->id]);
        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

     
}