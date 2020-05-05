<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $users = $this->mentionedUsers($event);
        $users->each->notify(new YouWereMentioned($event->reply, $event->thread));
    }

    public function mentionedUsers($event)
    {
        preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);
        $names = $matches[1];
        return $users = User::whereIn('name', $names)->get();
    }
}