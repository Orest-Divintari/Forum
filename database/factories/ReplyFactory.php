<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reply;
use Faker\Generator as Faker;
use App\User;
use App\Thread;
$factory->define(Reply::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'user_id' => factory(User::class),
        'thread_id' => factory(Thread::class)     
    ];
});
