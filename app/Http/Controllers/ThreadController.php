<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Requests\ThreadCreateRequest;
use App\Http\Requests\ThreadUpdateRequest;
use App\Thread;
use App\Trending;
use App\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $trending;
    public function __construct(Trending $trending)
    {
        $this->trending = $trending;
    }
    public function index(Channel $channel, ThreadFilters $filters)
    {

        $threads = $this->getThreads($channel, $filters);

        // $threads = $filters->apply($threadsBuilder);
        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending_threads' => $this->trending->get(),
        ]);
    }

    public function getThreads($channel, $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->paginate(25);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ThreadCreateRequest $request)
    {

        $thread = Thread::create([
            'title' => request('title'),
            'body' => request('body'),
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published');
    }

    public function update($channelId, Thread $thread, ThreadUpdateRequest $request)
    {

        $thread->update($request->validated());
        if (request()->expectsJson()) {

            return $thread->path;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $this->trending->push($thread);
        $thread->recordVisit();
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */

    public function destroy($channel, Thread $thread)
    {
        // delete the replies here || the alternative is to use onDelete('cascade')
        // $thread->replies()->delete();

        $this->authorize('update', $thread);
        // $thread->replies->each->delete();

        $thread->delete();
        if (request()->expectsJson()) {
            return response('Deleted', 200);
        }
        $name = auth()->user()->name;
        return redirect("/threads?by={$name}");
    }
}