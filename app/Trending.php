<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{

    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));

    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, $this->encode($thread));

    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    public function views($thread)
    {
        return Redis::zscore($this->cacheKey(), $this->encode($thread)) ?: 0;
    }

    protected function encode($thread)
    {
        return json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]);
    }
}