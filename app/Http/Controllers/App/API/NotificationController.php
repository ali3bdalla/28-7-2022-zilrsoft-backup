<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->unreadNotifications()->paginate(40);
    }

    public function markAsRead($notification, Request $request)
    {
        $request->user()->unreadNotifications()->whereId($notification)->get()->markAsRead();
    }
}
