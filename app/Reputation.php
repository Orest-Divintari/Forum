<?php

namespace App;

class Reputation
{

    protected $reputation_points = [
        'created_thread' => 10,
        'created_reply' => 2,
        'best_reply' => 50,
    ];
    public function award($user, $event)
    {
        $user->increment('reputation', $this->reputation_points[$event]);
    }
}