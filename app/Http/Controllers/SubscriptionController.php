<?php

namespace App\Http\Controllers;

use App\Thread;

class SubscriptionController extends Controller
{
    public function store($channelId, Thread $thread)
    {
        $thread->subscribe(auth()->id());
    }

    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe(auth()->id());

    }
}