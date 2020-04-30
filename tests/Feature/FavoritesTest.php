<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Reply;
use Facades\Tests\Setup\ThreadFactory;
use App\Favorite;

class FavoritesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    /** @test */
    public function an_authenticated_user_can_favor_any_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->post('/replies/' . $reply->id . '/favorites');

        $reply = Reply::find($reply->id);
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function guests_cannot_favorite_anything()
    {
        // $this->withoutExceptionHandling();
        $reply = create('App\Reply');
        $this->post('/replies/' . $reply->id . '/favorites')
            ->assertRedirect('login');
        // $this->assertDatabaseMissing('replies', ['body' => $reply->body]);
    }

    /** @test */
    public function an_authenticated_user_may_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create('App\Reply');
        try {
            $this->post('/replies/' . $reply->id . '/favorites');
            $this->post('/replies/' . $reply->id . '/favorites');
        } catch (Exception $e) {
            $this->fail('Did not excpet to insert the same record set twice');
        }
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function authorized_users_can_unfavorite_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $reply->favorite(auth()->id());

        $this->assertCount(1, $reply->favorites);

        $this->delete('/replies/' . $reply->id . "/favorites");
        $reply->refresh();
        $this->assertCount(0, $reply->favorites);
        $this->assertDatabaseMissing('favorites', [
            'favoritable_id' => $reply->id,
            'user_id' => auth()->id(),
            'favoritable_type' => 'App\Reply'
        ]);
    }

    /** @test */
    public function unauthorized_users_cannot_unlike_a_reply()
    {
        $reply = create('App\Reply');
        $reply->favorite($reply->creator->id);
        $this->delete('/replies/' . $reply->id . "/favorites")
            ->assertRedirect('login');

        $this->signIn();
        $this->delete('/replies/' . $reply->id . "/favorites")
            ->assertStatus(403);

        $this->assertDatabaseHas('favorites', [
            'favoritable_id' => $reply->id,
            'favoritable_type' => 'App\Reply',
            'user_id' => $reply->creator->id
        ]);
    }
}