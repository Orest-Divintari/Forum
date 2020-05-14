<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
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
        $this->signIn();
        $this->thread = create('App\Thread', ['user_id' => auth()->id()]);
    }

    /** @test */
    public function authorized_user_may_update_a_thread()
    {

        $this->patch($this->thread->path(), [
            'title' => 'changed title',
            'body' => 'changed body',

        ]);

        tap($this->thread->fresh(), function ($thread) {
            $this->assertEquals('changed title', $thread->title);
            $this->assertEquals('changed body', $thread->body);
        });

    }

    /** @test */
    public function unathorized_users_may_not_update_a_thread()
    {

        $thread = create('App\Thread');

        $this->patch($thread->path(), [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_thread_requires_a_body_when_updated()
    {

        $this->patch($this->thread->path(), [
            'title' => 'changed title',

        ])->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_thread_requires_a_title_when_updated()
    {

        $this->patch($this->thread->path(), [
            'body' => 'changed body',

        ])->assertSessionHasErrors('title');

    }

    /** @test */
    public function the_slug_of_a_thread_must_be_proper_when_the_the_title_is_updated()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'some title',
            'body' => 'some body',
        ]);

        $this->assertEquals('some-title', $thread->fresh()->slug);

    }

    /** @test */
    public function the_new_path_of_a_thread_is_returned_when_updated()
    {
        $response = $this->json('patch', $this->thread->path(), [
            'title' => 'new title',
            'body' => 'new body',
        ]);
        $this->assertEquals($response->getContent(), $this->thread->fresh()->path);

    }

}