<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $user = create('App\User');
        $this->json('post', '/api/users/' . $user->id . '/avatar')
            ->assertStatus(401);

    }

    /** @test  */
    public function a_user_must_provide_a_valid_avatar()
    {
        $user = $this->signIn();
        $this->json('post', '/api/users/' . $user->name . '/avatar', ['avatar' => 'invalid avatar'])
            ->assertStatus(422);

    }

    /** @test */
    public function an_authenticated_user_can_add_avatar()
    {
        $user = $this->signIn();
        Storage::fake('public');
        $this->json('post', '/api/users/' . $user->name . '/avatar', [
            'avatar' => $image = UploadedFile::fake()->image('avatar.jpg'),
        ]);
        Storage::disk('public')->assertExists('avatars/' . $image->hashName());
        $this->assertEquals('avatars/' . $image->hashName(), $user->avatar_path);
    }

}