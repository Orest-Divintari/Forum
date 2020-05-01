<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Thread;
use App\User;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'channel_id' => factory(Channel::class),
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'replies_count' => 0,
    ];
});