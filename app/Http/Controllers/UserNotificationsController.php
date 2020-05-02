<?php

namespace App\Http\Controllers;

use App\User;

class UserNotificationsController extends Controller
{

    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }

    public function index(User $user)
    {

        if (request()->expectsJson()) {
            return auth()->user()->unreadNotifications;
        }

    }
}