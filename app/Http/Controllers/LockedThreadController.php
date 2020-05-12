<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}