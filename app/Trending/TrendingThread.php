<?php

namespace App\Trending;

use Illuminate\Support\Facades\Redis;

class TrendingThread
{

    public function incrementScore($thread, $score = 1)
    {
        Redis::zincrby('trending_threads', $score, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));
    }

    public function popular($count = 5)
    {
        $count--;
        $threads = Redis::zrevrange('trending_threads', 0, $count);
        $popular_threads = array_map('json_decode', $threads);
        return $popular_threads;
    }

}