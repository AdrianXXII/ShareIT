<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    // Types of Reservations
    const TYPE_ONE_TIMES = 1;
    const TYPE_REPEATING = 2;

    // Priorities
    const PRIORITY_HEIGH = 1;
    const PRIORITY_MIDDLE = 2;
    const PRIORITY_LOW = 3;
    const PRIORITY_FLEXIBLE = 4;

    //  Automatic Teamstamp
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shared_object_id','user_id','recurring_resservation_id','type','priority','date','reason','manuel','deleted'];

    public static function checkValidPriority($priority){
        switch($priority){
            case self::PRIORITY_HEIGH:
                return self::PRIORITY_HEIGH;
            case self::PRIORITY_MIDDLE:
                return self::PRIORITY_MIDDLE;
            case self::PRIORITY_FLEXIBLE:
                return self::PRIORITY_FLEXIBLE;
            default:
                return self::PRIORITY_FLEXIBLE;
        }
    }

    public static function checkValidType($type){
        switch($type) {
            case self::TYPE_REPEATING:
                return self::TYPE_REPEATING;
            default:
                return self::TYPE_ONE_TIMES;
        }
    }

    public function template(){
        return $this->belongsTo(ReservationTemplate::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sharedObject(){
        return $this->belongsTo(User::class);
    }
}
