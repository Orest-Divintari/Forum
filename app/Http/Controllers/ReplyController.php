<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Inspections\Spam;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Exception;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread, ReplyRequest $request)
    {

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);
        $names = $matches[1];
        foreach ($names as $name) {
            $user = User::whereName($name)->first();

            if ($user) {

                $user->notify(new YouWereMentioned($reply, $thread));
            }
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