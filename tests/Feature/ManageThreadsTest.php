<?php

namespace Tests\Feature;

use App\Activity;
use App\Rules\Recaptcha;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->mock(Recaptcha::class, function ($mock) {
            $mock->shouldReceive('passes')->andReturn(true);
        });
    }
    /** @test */
    public function guests_may_not_create_forum_threads()
    {
        $this->post(route('threads.store'), [])
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

        $response = $this->publish_thread([
            'title' => 'some title',
            'body' => 'some body',
        ]);

        $this->get($response->getTargetUrl())
            ->assertSee('some title')
            ->assertSee('some body');

    }

    /** @test */
    public function a_new_user_that_hasnt_verified_his_email_cannot_publish_a_thread()
    {

        $user = create('App\User', ['email_verified_at' => null]);
        $this->signIn($user);
        $thread = raw('App\Thread');
        $this->post('/threads', $thread)
            ->assertRedirect(route('verification.notice'));

    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        //create first thread
        $thread = create('App\Thread', [
            'title' => 'foo title',
        ]);

        $recaptcha = ['g-recaptcha-response' => 'some token'];
        $this->assertEquals($thread->fresh()->slug, 'foo-title');
        // post the same thread for 2nd time
        $this->post(route('threads.store'), $thread->toArray() + $recaptcha);

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());
        // post the same thread for 3rd time
        $this->post(route('threads.store'), $thread->toArray() + $recaptcha);

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        // first create a thread with a title that ends with a number
        $thread = create('App\Thread', [
            'title' => 'some title 24',
        ]);

        // post the same thread and make sure that the second one will have the proper slug
        $this->post(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'some token']);

        $this->assertTrue(Thread::whereSlug('some-title-24-2')->exists());
    }

    /** @test */
    public function a_thread_requires_recaptcha()
    {
        // I unset the mocking Recaptcha class
        // in order to use the REAL Recaptcha rule when validation takes place
        unset(app()[Recaptcha::class]);
        $this->signIn();
        $thread = raw('App\Thread');

        $this->post(route('threads.store'), $thread + ['g-recaptcha-response' => 'some token'])
            ->assertSessionHasErrors('g-recaptcha-response');
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
        $user = $this->signIn();
        $thread = raw('App\Thread', $overrides + ['user_id' => $user->id]);
        return $this->post(route('threads.store'), $thread + ['g-recaptcha-response' => 'some token']);
    }

    /** @test */
    public function authorized_users_can_delete_a_thread()
    {
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

}