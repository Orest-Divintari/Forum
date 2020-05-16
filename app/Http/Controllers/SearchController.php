<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {

        $search = request('q');

        if (request()->expectsJson()) {
            return Thread::search($search)->paginate(25);
        }

        return view('threads.search', [
            'trending_threads' => $trending->get(),
        ]);
    }
}