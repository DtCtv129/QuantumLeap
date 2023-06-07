<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use App\Http\Library\ApiHelpers;

class NotificationsController extends Controller
{
    //
    use ApiHelpers;


    public function unreadNotificationsCount(Request $request)
{
    $user = $request->user();

    $unreadCount = $user->unreadNotifications()->count();

    return $this->OnSuccess($unreadCount,'Le Nombre de Notif non Lues');
}

    public function getNotificationsForUser(Request $request)
{
    $user = $request->user();

    $notifications = $user->notifications;

    return $this->OnSuccess($notifications,'Toutes les Notifs');
}

    public function markAsRead(Request $request, $id)
{
    $user = $request->user();
    $notification = $user->notifications()->where('id', $id)->first();
    if ($notification) {
        $notification->markAsRead();
        return $this->OnSuccess($notification,'Notification Lue');
    } else {
        return $this->OnSuccess($notification,'Notification Introuvale');
    }
}





}
