<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Reply $reply)
    {
        return $reply->creator->is($user);
    }

    public function create(User $user, Reply $reply)
    {

        $lastReply = $user->fresh()->lastReply;

        if (!$lastReply) {
            return true;
        }

        // deny auth if last reply was just published
        return !$lastReply->wasJustPublished();
    }
}