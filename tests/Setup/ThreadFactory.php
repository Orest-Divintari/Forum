<?php

namespace Tests\Setup;

use App\Channel;
use App\Reply;
use App\User;

class ThreadFactory
{

    protected $repliesCount = 0;
    protected $channelId = '';
    protected $user;

    public function create()
    {
        $this->user = $this->user ?: factory(User::class)->create();

        $thread = factory('App\Thread')
            ->create([
                'user_id' => $this->user->id,
                'channel_id' => $this->channelId ?: factory(Channel::class),
            ]);

        factory(Reply::class, $this->repliesCount)
            ->create([
                'user_id' => factory(User::class),
                'thread_id' => $thread->id,
            ]);

        return $thread;
    }
    public function withChannel($channelName)
    {
        $channel = factory(Channel::class)->create([
            'name' => $channelName,
            'slug' => $channelName,
        ]);

        $this->channelId = $channel->id;
        return $this;
    }

    public function withReplies($repliesCount)
    {
        $this->repliesCount = $repliesCount;
        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

}