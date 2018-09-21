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

    // Relationships
    /**
     * Returns the User that created it
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Returns the user that updated it last
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Returns the users  this Object is shared with
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Returns the reservations
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Functions
    /**
     * Checks if the user is in the list
     * @param User $user
     * @return bool
     */
    public function hasUser(User $user)
    {
        return count($this->users()->get()->where('id', $user->id)) >= 1;
    }


    /**
     * Returns the created timepoint as a formated string
     * @return string
     */
    public function createdAt(){
        return (new Carbon($this->created_at))->format('l, F jS Y - H:i');
    }

    /**
     * Returns the update timepoint as a formated string
     * @return string
     */
    public function updatedAt(){
        return (new Carbon($this->created_at))->format('l, F jS Y - H:i');
    }

    /**
     * Adds the given user to SharedObject
     * @param User $user
     * @return bool
     */
    public function addUser(User $user){
        if(!$this->hasUser($user)){
            $this->users()->attach($user);
            return true;
        }
        return false;
    }

    /**
     * Removes the given user to SharedObject
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user){
        if($this->hasUser($user)){
            $this->users()->detach($user);
            return true;
        }
        return false;
    }

    /**
     * Returns the relavant reservations for the user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelaventReservations(){
        return $this->reservations()->where('date','>=',new Carbon())->where('deleted',false)->orderBy('date','asc')->get();
    }
}
