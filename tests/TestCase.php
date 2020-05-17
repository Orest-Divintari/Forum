<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null)
    {

        $user = $user ?: factory(User::class)->create();
        $this->actingAs($user);
        return $user;

    }

    protected function signInAdmin($user = null)
    {
        $user = $user ?: factory(User::class)->create(['email' => config('insomnia.administrators')[0]]);

        $this->actingAs($user);
        return $user;
    }
}