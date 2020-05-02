<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread)
    {

        request()->validate([
            'body' => 'required',
        ]);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply;
        }
        return redirect($thread->path());
    }

    public function destroy(Reply $reply)
    {

        $this->authorize('update', $reply);
        $reply->delete();
        // return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(request()->validate(['body' => 'required']));
    }
}