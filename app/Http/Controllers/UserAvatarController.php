<?php

namespace App\Http\Controllers;

use App\User;

class UserAvatarController extends Controller
{
    public function store(User $user)
    {
        request()->validate(['avatar' => 'image']);

        auth()->user()->update([
            'avatar_path' => request('avatar')->store('avatars'),
        ]);
        return response([], 204);
    }
}