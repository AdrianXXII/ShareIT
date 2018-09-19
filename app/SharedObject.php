<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SharedObject extends Model
{

    //
    use Notifiable;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['designation','description','created_at','created_by','updated_at','updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function hasUser(User $user)
    {
        return count($this->users()->get()->where('id', $user->id)) >= 1;
    }

    public function createdAt(){
        return (new Carbon($this->created_at))->format('l, F jS Y - H:i:s');
    }

    public function updatedAt(){
        return (new Carbon($this->created_at))->format('l, F jS Y - H:i:s');
    }

    public function notifyEveryone($subject, $content){
        foreach($this->users as $user){
            $notification = new Notification();
            $notification->email = $user->email;
            $notification->subject = $subject;
            $notification->content = $content;
            $notification->created = new Carbon();
            $notification->save();
        }
    }

    public function addUser(User $user){
        if(!$this->hasUser($user)){
            $this->users()->attach($user);
            return true;
        }
        return false;
    }

    public function removeUser(User $user){
        if($this->hasUser($user)){
            $this->users()->detach($user);
            return true;
        }
        return false;
    }

    public function getRelaventReservations(){
        return $this->reservations()->where('deleted',false)->orderBy('date','asc')->get();
    }
}
