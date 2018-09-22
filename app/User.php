<?php

namespace App;

use Carbon\Carbon;
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

    /**
     * Returns user's SharedObjects
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sharedObjects()
    {
        return $this->belongsToMany(SharedObject::class);
    }

    /**
     * Returns user's reservations
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Returns user's templates
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(ReservationTemplate::class);
    }

    /**
     * Returns sharedObjects created by the user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdSharedObjects()
    {
        return $this->hasMany('App\SharedObject','created_by');
    }

    /**
     * Returns sharedObjects updated last by the user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function updatedSharedObjects()
    {
        return $this->hasMany('App\SharedObject','updated_by');
    }

    // Functions
     public function setCasts($casts)
    {
        $this->casts = $casts;
    }

    /**
     * Returns full name of the user
     * @return string
     */
    public function getFullname(){
        return $this->firstname . " " . $this->lastname;
    }

    /**
     * Returns the relavent reservations for the users
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelaventReservations(){
        return $this->reservations()
            ->where('deleted',false)
            ->whereDate('date','>=',Carbon::today())
            ->orderBy('date','asc')
            ->orderBy('from','asc')
            ->orderBy('to','asc')
            ->get();
    }
}
