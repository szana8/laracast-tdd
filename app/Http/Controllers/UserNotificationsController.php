<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{

    /**
     * UserNotificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    /**
     * @param User $user
     * @param $notificationId
     * @return
     */
    public function destroy(User $user, $notificationId)
    {
        return auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
