<?php

namespace Tests\Feature;

use App\Favorite;
use App\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
    public function authenticated_users_can_unfavorite_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $reply->favorite(auth()->id());

        $this->assertCount(1, $reply->fresh()->favorites);

        $this->delete(route('replies.unfavorite', $reply->id));

        $this->assertCount(0, $reply->fresh()->favorites);
        $this->assertDatabaseMissing('favorites', [
            'favoritable_id' => $reply->id,
            'user_id' => auth()->id(),
            'favoritable_type' => 'App\Reply',
        ]);
    }

}