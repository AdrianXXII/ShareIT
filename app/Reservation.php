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

    // Relations
    /**
     * Gets the template for this Reservation if one exists
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template(){
        return $this->belongsTo(ReservationTemplate::class,'recurring_resservation_id','id');
    }

    /**
     * Returns the User of the Reservation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the SharedObject for this Reservation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sharedObject(){
        return $this->belongsTo(SharedObject::class);
    }

    /**
     * Returns some of the conflicting Reservations
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conflictingRight(){
        return $this->belongsToMany(Self::class, 'conflicting_reservations', 'reservation_2_id','reservation_1_id' );
    }

    /**
     * Returns some of the conflicting Reservations
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conflictingLeft(){
        return $this->belongsToMany(Self::class, 'conflicting_reservations', 'reservation_1_id','reservation_2_id' );
    }

    /**
     * Corrects the given priority is valid
     * @param $priority The priority to be used
     * @return int A valid priority
     */
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

    /**
     * Corrects if the given type is valid for Reservations
     * @param $type Type to be used
     * @return int A valid Type
     */
    public static function checkValidType($type){
        switch($type) {
            case self::TYPE_REPEATING:
                return self::TYPE_REPEATING;
            default:
                return self::TYPE_ONE_TIME;
        }
    }

    /**
     * Returns the whole list of conflicts
     * @return Collection all of the conflicts
     */
    public function conflicts(){
        $conflicts = new Collection();
        $conflicts = $conflicts->merge($this->conflictingLeft);
        $conflicts = $conflicts->merge($this->conflictingRight);
        return $conflicts;
    }

    /**
     * Creates a reservation for the given day, based on the given Template
     * @param ReservationTemplate $template
     * @param $date
     */
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

    /**
     * Saves the Reservation
     * @param array $options
     */
    public function save(array $options = [])
    {
        $this->conflictingLeft()->detach();
        $this->conflictingRight()->detach();
        parent::save($options);
        $this->setConflicts();
        parent::save($options);
    }

    /**
     * Creates the list of conflicts for this reservation
     */
    public function setConflicts()
    {
        $to = $this->getTo()->format('H:i:s');
        $from = $this->getTo()->format('H:i:s');
        $conflicts = Reservation::where('id','<>',$this->id)
            ->where('shared_object_id','=',$this->sharedObject->id)
            ->where('deleted',false)
            ->whereDate('date','=',$this->date)
            ->whereRaw(
                '(? between `from` AND `to` OR ? between `from` AND `to` OR `from` between ? AND ? OR `to` between ? AND ?)',
            [$from, $to, $from, $to, $from, $to])
            ->get();
        if($conflicts->count() >= 1){
            $this->conflictingLeft()->attach($conflicts);
        }
        echo $conflicts;
    }

    /**
     * Returns the Date
     * @return Carbon|null
     */
    public function getDate()
    {
        if(isset($this->date)){
            return new Carbon($this->date);
        } else {
            return null;
        }
    }

    /**
     * Returns the Date as a formated String
     * @return string
     */
    public function getDateStr()
    {
        $date = $this->getDate();
        return (isset($date)) ? $date->format('Y-m-d') : '';
    }

    /**
     * Returns the to time
     * @return null|static
     */
    public function getTo()
    {
        if(isset($this->to)){
            return Carbon::createFromTimeString($this->to, 'Europe/Berlin');
        }
        else {
            return null;
        }
    }

    /**
     * Returns the to time as a formated string
     * @return string
     */
    public function getToStr(){
        $toTime = $this->getTo();
        return (isset($toTime)) ? $toTime->format('H:i') : '';
    }

    /**
     * Returns the from date
     * @return null|static
     */
    public function getfrom()
    {
        if(isset($this->from)){
            return Carbon::createFromTimeString($this->from, 'Europe/Berlin');
        }
        else {
            return null;
        }
    }

    /**
     * Returns from time as a formated String
     * @return string
     */
    public function getFromStr()
    {
        $fromTime = $this->getFrom();
        return (isset($fromTime)) ? $fromTime->format('H:i') : '';
    }

    /**
     * Delete reservation
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->conflictingLeft()->detach();
        $this->conflictingRight()->detach();
        return parent::delete();
    }

    /**
     * Returns the String for the current Priority
     * @return array|null|string
     */
    public function getPriority(){
        switch($this->priority){
            case self::PRIORITY_FLEXIBLE:
                return __('messages.flexible');
                break;
            case self::PRIORITY_HIGH:
                return __('messages.high');
                break;
            case self::PRIORITY_MIDDLE:
                return __('messages.middle');
                break;
            case self::PRIORITY_LOW:
                return __('messages.low');
                break;
            default:
                return __('messages.unknown');
                break;
        }
    }


    /**
     * Returns the event start, time and date in the required format for ical
     * @return string
     */
    public function getEventStart(){
        $toTime = $this->getTo();
        $date = $this->getDate();
        $date->setTime($toTime->hour,$toTime->minute);
        return $date->format('Ymd') . "T" . $date->format('His') . "Z";
    }



    /**
     * Returns the event end, time and date in the required format for ical
     * @return string
     */
    public function getEventEnd(){
        $fromTime = $this->getFrom();
        $date = $this->getDate();
        $date->setTime($fromTime->hour, $fromTime->minute);
        return $date->format('Ymd') . "T" . $date->format('His') . "Z";
    }

    /**
     * Returns the event description/summary for the ical export
     * @return array|null|string
     */
    public function getEventSummary(){
        $msg = __('messages.event-summary-1',['USERNAME' => $this->user->username, 'SHARED_OBJECT' => $this->sharedObject->designation ]);
        if(strlen($this->reason) >  0){
            $msg .= __('messages.event-summary-2', ['REASON' => $this->reason]);
        }
        return  $msg;
    }
}
