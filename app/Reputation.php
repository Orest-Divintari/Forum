<?php

namespace App;

class Reputation
{
    const REPLY_POSTED = 2;
    const REPLY_DELETED = 2;
    const REPLY_FAVORITED = 5;
    const REPLY_UNFAVORITED = 5;
    const THREAD_CREATED = 10;
    const THREAD_DELETED = 10;
    const BEST_REPLY = 50;

    public function award($user, $points)
    {
        $user->increment('reputation', $points);
    }

    public function unaward($user, $points)
    {
        $user->decrement('reputation', $points);
    }
}