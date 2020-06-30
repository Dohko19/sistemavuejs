<?php

namespace App\Http\Controllers;

use App\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function get()
    {
//        return Notification::all();
        $unreadNotification = Auth::user()->unreadNotifications();
        $fechaActual = Carbon::now()->format('Y-m-d');
        foreach ($unreadNotification as $notification){
            if ($fechaActual != $notification->created_at->toDaesString()){
                $notification->markAsRead();
            }
        }
        return Auth::user()->unreadNotifications;
    }
}
