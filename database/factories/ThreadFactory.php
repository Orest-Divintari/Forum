<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Thread;
use Faker\Generator as Faker;
use App\User;
use App\Channel;
$factory->define(Thread::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'channel_id' => factory(Channel::class),
        'title' => $faker->sentence,
        'body'  => $faker->paragraph
    ];
});
