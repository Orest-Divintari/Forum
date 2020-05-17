<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministratorTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function an_administrator_can_access_the_admin_panel()
    {
        $this->signInAdmin();
        $this->get('/admin')
            ->assertOk();
    }

    /** @test */
    public function a_non_admin_cannot_access_the_admin_panel()
    {
        $this->signIn();
        $this->get('/admin')
            ->assertStatus(403);
    }
}