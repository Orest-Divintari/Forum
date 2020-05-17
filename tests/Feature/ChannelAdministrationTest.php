<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelAdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signInAdmin();
    }
    /** @test */
    public function the_admin_can_access_the_channels_administration_panel()
    {
        create('App\Channel', [
            'name' => 'test channel',
            'slug' => 'test slug',
        ]);

        $this->get(route('admin.channels.index'))
            ->assertOk()
            ->assertSee('test channel')
            ->assertSee('test slug');
    }

    /** @test */
    public function the_admin_can_access_the_form_to_create_channels()
    {
        $this->get(route('admin.channels.create'))
            ->assertOk();
    }

    /** @test */
    public function admin_can_create_a_new_channel()
    {
        $this->withExceptionHandling();
        $this->post(route('admin.channels.store'), [
            'name' => 'new channel',
            'description' => 'new description for channel',
        ]);

        $this->assertDatabaseHas('channels', ['name' => 'new channel', 'description' => 'new description for channel']);
    }

    /** @test */
    public function only_the_admin_can_create_a_new_channel()
    {
        $this->signIn();
        $this->post(route('admin.channels.store'), [
            'name' => 'new channel',
            'description' => 'new description for channel',
        ])->assertStatus(403);
    }
    /** @test */
    public function a_new_channel_requires_a_name()
    {

        $this->post(route('admin.channels.store'), [
            'description' => 'new description for channel',
        ])->assertSessionHasErrors('name');

    }

    /** @test */
    public function a_new_channel_requires_a_description()
    {
        $this->post(route('admin.channels.store'), [
            'name' => 'new channel',
        ])->assertSessionHasErrors('description');
    }
}