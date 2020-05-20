<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChannelStoreRequest;

class ChannelsController extends Controller
{
    public function index()
    {
        return view('admin.channels.index');

    }
    public function create()
    {
        return view('admin.channels.create');
    }

    public function store(ChannelStoreRequest $request)
    {

        $request->persist();
        return redirect(route('admin.channels.index'))->with('flash', 'A new channel has been created');

    }
}