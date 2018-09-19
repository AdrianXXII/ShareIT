<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    // Types of Reservations
    const TYPE_ONE_TIME = 1;
    const TYPE_REPEATING = 2;

    // Priorities
    const PRIORITY_HIGH = 1;
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
            case self::PRIORITY_HIGH:
                return self::PRIORITY_HIGH;
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
                return self::TYPE_ONE_TIME;
        }
    }

    public function template(){
        return $this->belongsTo(ReservationTemplate::class,'recurring_resservation_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sharedObject(){
        return $this->belongsTo(SharedObject::class);
    }

    public function conflictingRight(){
        return $this->belongsToMany(Self::class, 'conflicting_reservations', 'reservation_2_id','reservation_1_id' );
    }

    public function conflictingLeft(){
        return $this->belongsToMany(Self::class, 'conflicting_reservations', 'reservation_1_id','reservation_2_id' );
    }

    public function conflicts(){
        $conflicts = new Collection();
        $conflicts->merge($this->conflictingLeft);
        $conflicts->merge($this->conflictingRight);
        return $conflicts;
    }

    public static function fromTemplate(ReservationTemplate $template, $date){
        $reservation = new Reservation();
        $reservation->date = $date;
        $reservation->manuel = false;
        $reservation->deleted = false;
        $reservation->type = Reservation::TYPE_REPEATING;
        $reservation->reason = $template->reason;
        $reservation->priority = $template->priority;
        $reservation->to = $template->to;
        $reservation->from = $template->from;
        $reservation->template()->associate($template);
        $reservation->user()->associate($template->user);
        $reservation->sharedObject()->associate($template->sharedObject);
        $reservation->save();
    }

    public function save(array $options = [])
    {
        $this->conflictingLeft()->detach();
        $this->conflictingRight()->detach();
        parent::save($options);
        $this->setConflicts();
    }

    public function setConflicts()
    {
        $conflicts = Reservation::where('id','!=',$this->id)
            ->where('shared_object_id',$this->sharedObject->id)->where('deleted',false)
            ->whereRaw('(? between `from` AND `to` OR ? between `from` AND `to` OR `from` between ? AND ? OR `to` between ? AND ?)',
            [$this->to,$this->from,$this->to,$this->from,$this->to,$this->from])
            ->get();
        if($conflicts->count() >= 1){
            $this->conflictingLeft()->detach($conflicts);
        }
    }

    public function getDate()
    {
        if(isset($this->date)){
            return new Carbon($this->date);
        } else {
            return null;
        }
    }

    public function getDateStr()
    {
        $date = $this->getDate();
        return (isset($date)) ? $date->format('Y-m-d') : '';
    }

    public function getTo()
    {
        if(isset($this->to)){
            return Carbon::createFromTimeString($this->to, 'Europe/Berlin');
        }
        else {
            return null;
        }
    }

    public function getToStr(){
        $toTime = $this->getTo();
        return (isset($toTime)) ? $toTime->format('H:m') : '';
    }

    public function getfrom()
    {
        if(isset($this->from)){
            return Carbon::createFromTimeString($this->from, 'Europe/Berlin');
        }
        else {
            return null;
        }
    }

    public function getFromStr()
    {
        $fromTime = $this->getFrom();
        return (isset($fromTime)) ? $fromTime->format('H:m') : '';
    }

    public function delete()
    {
        $this->conflictingLeft()->detach();
        $this->conflictingRight()->detach();
        return parent::delete();
    }

    public function getPriority(){
        switch($this->priority){
            case self::PRIORITY_FLEXIBLE:
                return __('messages.flexible');
                break;
            case self::PRIORITY_FLEXIBLE:
                return __('messages.heigh');
                break;
            case self::PRIORITY_FLEXIBLE:
                return __('messages.middle');
                break;
            case self::PRIORITY_FLEXIBLE:
                return __('messages.low');
                break;
            default:
                return __('messages.unknown');
                break;
        }
    }
}
