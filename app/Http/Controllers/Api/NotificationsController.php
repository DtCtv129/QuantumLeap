<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Notifications\DemandeStatusNotification;
use App\Models\User;
use App\Models\Demande;
// use Illuminate\Support\Facades\Notification;
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

public function push(Request $request){
    $user = $request->user(); 
    if ($this->isAdmin($user)) {
    
    Notification::create([
        "message"=>$request->message,
        "user_id"=>$request->userId
    ]);          
        return $this->onSuccess("Success", 'Nortification Pushed Successfully');   
    }
        return $this->onError(401,"Unauthorized Access");
    }
    public function get(Request $request){
        $user = $request->user(); 
       
        if($user){$notifications=Notification::where('id',$user->id)->all();          
            return $this->onSuccess($notifications, 'Nortification Pushed Successfully');   
            }
        
            return $this->onError(401,"Unauthorized Access");
        }
}
