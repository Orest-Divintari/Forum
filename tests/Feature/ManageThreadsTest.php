<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Facades\Tests\Setup\ThreadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageThreadsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_forum_threads()
    {
        $this->post('/threads', [])
            ->assertRedirect('login');
    }

    /** @test */
    public function guests_may_not_see_the_thread_form()
    {
        $this->get('/threads/create')
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)
            ->raw([
                'user_id' => auth()->id(),
            ]);
        $response = $this->post('/threads', $thread);
        $this->assertDatabaseHas('threads', $thread);

        $thread = Thread::first();
        $response->assertRedirect($thread->path());

        $this->get($thread->path())
            ->assertSee($thread->title);
    }

    /** @test */
    public function a_new_user_that_hasnt_verified_his_email_cannot_publish_a_thread()
    {

        $user = create('App\User', ['email_verified_at' => null]);
        $this->signIn($user);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHas('flash');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();
        //create first thread
        $thread = create('App\Thread', [
            'title' => 'foo title',
            'slug' => 'foo-title',
        ]);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');
        // post the same thread for 2nd time
        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());
        // post the same thread for 3rd time
        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }
    /** @test */
    public function a_thread_requires_a_title()
    {

        $this->publish_thread(['title' => ''])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_channel()
    {

        $this->publish_thread(['channel_id' => ''])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publish_thread(['body' => ''])
            ->assertSessionHasErrors('body');
    }

    public function publish_thread($overrides)
    {
        $this->signIn();
        $thread = raw('App\Thread', $overrides);
        return $this->post('/threads', $thread);
    }

    /** @test */
    public function authorized_users_can_delete_a_thread()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $thread = ThreadFactory::ownedBy($user)
            ->withReplies(1)
            ->create();
        $reply = $thread->replies[0];

        $this->delete($thread->path());
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertCount(0, Activity::all());
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        //unauthenticated users can't delete
        $thread = create('App\Thread');
        $this->delete($thread->path())
            ->assertRedirect('login');
        //unathorized users cant delete
        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }

    // /** @test */
    // public function guests_may_not_see_the_thread_creation_form()
    // {
    //     $user = factory(User::class)->create();
    //     $this->get('/threads/create')
    //         ->assertRedirect('login');
    // }

    // /** @test */
    // public function guests_may_not_create_threads()
    // {
    //     $user = factory(User::class)->create();
    //     $threadAttributes = [
    //         'title' => 'test thread title ',
    //         'body' => 'test thread body',
    //         'user_id' => $user->id
    //     ];

    //     $this->post('/threads', $threadAttributes)
    //         ->assertRedirect('login');

    //     $this->assertDatabaseMissing('threads', $threadAttributes);
    // }
    // /** @test */
    // public function a_user_can_create_a_thread()
    // {
    //     $authenticatedUser = $this->signIn();
    //     $this->actingAs($authenticatedUser)->get('/threads/create')
    //         ->assertOk();

    //     $threadAttributes = [
    //         'title' => 'test thread title',
    //         'body' => 'test thread body',
    //         'user_id' => $authenticatedUser->id
    //     ];

    //     $this->actingAs($authenticatedUser)->post('/threads', $threadAttributes)
    //         ->assertSee($threadAttributes['title']);

    //     $this->assertDatabaseHas('threads', $threadAttributes);

    // }
}