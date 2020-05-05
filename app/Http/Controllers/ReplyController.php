<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Inspections\Spam;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread, ReplyRequest $request)
    {

        if (Gate::denies('create', new Reply)) {
            return response('You are posting too frequently', 422);
        }

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
    }

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);
        try {
            request()->validate(['body' => ['required', new SpamFree]]);
            $reply->update(['body' => request('body')]);
        } catch (Exception $e) {
            return response("Sorry, your reply could not be saved at this time", 422);
        }
    }

}