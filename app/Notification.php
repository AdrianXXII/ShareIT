<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    public $timestamps = false;
    const STATUS_PENDING = 'p';
    const STATUS_SENDING = 's';
    const STATUS_SUCCESS = 'o';
    const STATUS_FAILED  = 'x';
    const STATUS_GIVEN_UP = 'f';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','subject','content','status'];

    public static function shareWithUserNotification(User $user, User $addedBy, $sharedObject){
        $notification = new Notification();
        $notification->email = $user->email;
        $notification->subject = __('messages.added-to-shared-object', ['SHARED_OBJECT' => $sharedObject->designation]);
        $notification->content = __('messages.added-to-shared-object', ['NAME' => $user->name,'SHARED_OBJECT' => $sharedObject->designation, 'USERNAME' => $addedBy->username]);
        $notification->status = self::STATUS_PENDING;
        $notification->save();
    }

    public static function removeUserFromSharedObjectNotification(User $user, User $addedBy, $sharedObject){
        $notification = new Notification();
        $notification->email = $user->email;
        $notification->subject = __('messages.remove-to-shared-object', ['SHARED_OBJECT' => $sharedObject->designation]);
        $notification->content = __('messages.remove-to-shared-object', ['NAME' => $user->name,'SHARED_OBJECT' => $sharedObject->designation, 'USERNAME' => $addedBy->username]);
        $notification->status = self::STATUS_PENDING;
        $notification->save();
    }
}
