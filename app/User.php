<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'firstname', 'lastname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sharedObjects()
    {
        return $this->belongsToMany(SharedObject::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function templates()
    {
        return $this->hasMany(ReservationTemplate::class);
    }

    public function createdSharedObjects()
    {
        return $this->hasMany('App\SharedObject','created_by');
    }

    public function updatedSharedObjects()
    {
        return $this->hasMany('App\SharedObject','updated_by');
    }

     public function setCasts($casts)
    {
        $this->casts = $casts;
    }

    public function getFullname(){
        return $this->firstname . " " . $this->lastname;
    }

    public function getRelaventReservations(){
        return $this->reservations()->where('deleted',false)->orderBy('date','asc')->get();
    }
}
