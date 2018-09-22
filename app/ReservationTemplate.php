<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReservationTemplate extends Model
{
    //
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shared_object_id','user_id','weekly_frequency','monthly_frequency','yearly_frequency','is_day_based','from','to','reason','monday','tueday','wednesday','thursday','friday','saturday','sunday','priority','date','month','reason','from','to','start_date','end_date'];

    // Releationships
    /**
     * Returns the Templates User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the Templates SharedObject
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sharedObject(){
        return $this->belongsTo(SharedObject::class);
    }

    /**
     * Returns the Reservations created based on this Template
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations(){
        return $this->hasMany(Reservation::class,'recurring_resservation_id');
    }

    // Functions
    /**
     * Saves the Template and creates the Reservations based of of it
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $rtn = parent::save($options);
        $this->createReservations();
        return $rtn;
    }

    /**
     * Delete the Template and it's Reservations
     * @return int
     */
    public function createReservations(){
        $res = $this->reservations()->where('manuel',false)->where('date','>=',new Carbon())->delete();
        $today = Carbon::today();
        $endDate = $this->getEndDate();
        $next_date = Carbon::create($this->getStartDate()->year, $this->month, $this->date);
        $count = 0;

        if ($next_date->lessThanOrEqualTo($today)){
            if ($this->is_day_based) {
                while ($next_date->isbefore($today)){
                    if($this->weekly_frequency){
                        $next_date->addWeeks(1);
                    }
                    elseif($this->monthly_frequency){
                        $next_date->addMonths(1);
                    }
                    else {
                        $next_date->addYears(1);
                    }
                }
            }
            else {
                $next_date = $today;
                switch($next_date->dayOfWeekIso){
                    case 1: // Monday
                        if(!$this->monday){
                            if($this->tuesday){
                                $next_date->addDays(1);
                            }
                            elseif($this->wednesday){
                                $next_date->addDays(2);
                            }
                            elseif($this->thursday){
                                $next_date->addDays(3);
                            }
                            elseif($this->friday){
                                $next_date->addDays(4);
                            }
                            elseif($this->saturday){
                                $next_date->addDays(5);
                            }
                            elseif($this->sunday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 8<br>';
                                return;
                            }
                        }
                        break;
                    case 2: // tuesday
                        if(!$this->tuesday){
                            if($this->wednesday){
                                $next_date->addDays(1);
                            }
                            elseif($this->thursday){
                                $next_date->addDays(2);
                            }
                            elseif($this->friday){
                                $next_date->addDays(3);
                            }
                            elseif($this->saturday){
                                $next_date->addDays(4);
                            }
                            elseif($this->sunday){
                                $next_date->addDays(5);
                            }
                            elseif($this->monday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 7<br>';
                                return;
                            }
                        }
                        break;
                    case 3: // wednesday
                        if(!$this->wednesday){
                            if($this->thursday){
                                $next_date->addDays(1);
                            }
                            elseif($this->friday){
                                $next_date->addDays(2);
                            }
                            elseif($this->saturday){
                                $next_date->addDays(3);
                            }
                            elseif($this->sunday){
                                $next_date->addDays(4);
                            }
                            elseif($this->monday){
                                $next_date->addDays(5);
                            }
                            elseif($this->tuesday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 6<br>';
                                return;
                            }
                        }
                        break;
                    case 4: // Thursday
                        if(!$this->thursday){
                            if($this->friday){
                                $next_date->addDays(1);
                            }
                            elseif($this->saturday){
                                $next_date->addDays(2);
                            }
                            elseif($this->sunday){
                                $next_date->addDays(3);
                            }
                            elseif($this->monday){
                                $next_date->addDays(4);
                            }
                            elseif($this->tuesday){
                                $next_date->addDays(5);
                            }
                            elseif($this->wednesday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 5<br>';
                                return;
                            }
                        }
                        break;
                    case 5: // Friday
                        if(!$this->friday){
                            if($this->saturday){
                                $next_date->addDays(1);
                            }
                            elseif($this->sunday){
                                $next_date->addDays(2);
                            }
                            elseif($this->monday){
                                $next_date->addDays(3);
                            }
                            elseif($this->tuesday){
                                $next_date->addDays(4);
                            }
                            elseif($this->wednesday){
                                $next_date->addDays(5);
                            }
                            elseif($this->thursday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 4<br>';
                                return;
                            }
                        }
                        break;
                    case 6: // Saturday
                        if(!$this->saturday){
                            if($this->sunday){
                                $next_date->addDays(1);
                            }
                            elseif($this->monday){
                                $next_date->addDays(2);
                            }
                            elseif($this->tuesday){
                                $next_date->addDays(3);
                            }
                            elseif($this->wednesday){
                                $next_date->addDays(4);
                            }
                            elseif($this->thursday){
                                $next_date->addDays(5);
                            }
                            elseif($this->friday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 3<br>';
                                return;
                            }
                        }
                        break;
                    case 7: // Sunday
                        if(!$this->sunday){
                            if($this->monday){
                                $next_date->addDays(1);
                            }
                            elseif($this->tuesday){
                                $next_date->addDays(2);
                            }
                            elseif($this->wednesday){
                                $next_date->addDays(3);
                            }
                            elseif($this->thursday){
                                $next_date->addDays(4);
                            }
                            elseif($this->friday){
                                $next_date->addDays(5);
                            }
                            elseif($this->saturday){
                                $next_date->addDays(6);
                            }
                            else {
                                // echo 'nr. 2<br>';
                                return;
                            }
                        }
                        break;
                    default:
                        // echo 'nr. 1<br>';
                        return;
                        break;
                }
            }
        }

        while ($next_date <= $endDate) {
            $count++;
            $last_date = $next_date;
            Reservation::fromTemplate($this, $last_date);
            $next_date = clone $last_date;
            // echo $startDate  . " - " . $last_date . " - " . $endDate . "<br>";
            // echo $this->is_day_based . "<br>";

            if ($this->is_day_based) {
                if ($this->weekly_frequency) {
                    $next_date->addWeeks(1);
                } elseif ($this->monthly_frequency) {
                    $next_date->addMonths(1);
                } else {
                    $next_date->addYears(1);

                }
            } else {
                if ($this->weekly_frequency) {
                    switch ($last_date->dayOfWeekIso) {
                        case 1: // Monday
                            if ($this->tuesday) {
                                $next_date->addDays(1);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(2);
                            } elseif ($this->thursday) {
                                $next_date->addDays(3);
                            } elseif ($this->friday) {
                                $next_date->addDays(4);
                            } elseif ($this->saturday) {
                                $next_date->addDays(5);
                            } elseif ($this->sunday) {
                                $next_date->addDays(6);
                            } elseif ($this->monday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'no. 20<br>';
                                break 2;
                            }
                            break;
                        case 2: // tuesday
                            if ($this->wednesday) {
                                $next_date->addDays(1);
                            } elseif ($this->thursday) {
                                $next_date->addDays(2);
                            } elseif ($this->friday) {
                                $next_date->addDays(3);
                            } elseif ($this->saturday) {
                                $next_date->addDays(4);
                            } elseif ($this->sunday) {
                                $next_date->addDays(5);
                            } elseif ($this->monday) {
                                $next_date->addDays(6);
                            } elseif ($this->tuesday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'no. 19<br>';
                                break 2;
                            }
                            break;
                        case 3: // wednesday
                            if ($this->thursday) {
                                $next_date->addDays(1);
                            } elseif ($this->friday) {
                                $next_date->addDays(2);
                            } elseif ($this->saturday) {
                                $next_date->addDays(3);
                            } elseif ($this->sunday) {
                                $next_date->addDays(4);
                            } elseif ($this->monday) {
                                $next_date->addDays(5);
                            } elseif ($this->tuesday) {
                                $next_date->addDays(6);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'no. 18<br>';
                                break 2;
                            }
                            break;
                        case 4: // Thursday
                            if ($this->friday) {
                                $next_date->addDays(1);
                            } elseif ($this->saturday) {
                                $next_date->addDays(2);
                            } elseif ($this->sunday) {
                                $next_date->addDays(3);
                            } elseif ($this->monday) {
                                $next_date->addDays(4);
                            } elseif ($this->tuesday) {
                                $next_date->addDays(5);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(6);
                            } elseif ($this->thursday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'nr. 11<br>';
                                return;
                            }
                            break;
                        case 5: // Friday
                            if ($this->saturday) {
                                $next_date->addDays(1);
                            } elseif ($this->sunday) {
                                $next_date->addDays(2);
                            } elseif ($this->monday) {
                                $next_date->addDays(3);
                            } elseif ($this->tuesday) {
                                $next_date->addDays(4);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(5);
                            } elseif ($this->thursday) {
                                $next_date->addDays(6);
                            } elseif ($this->friday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'no. 17<br>';
                                break 2;
                            }
                            break;
                        case 6: // Saturday
                            if ($this->sunday) {
                                $next_date->addDays(1);
                            } elseif ($this->monday) {
                                $next_date->addDays(2);
                            } elseif ($this->tuesday) {
                                $next_date->addDays(3);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(4);
                            } elseif ($this->thursday) {
                                $next_date->addDays(5);
                            } elseif ($this->friday) {
                                $next_date->addDays(6);
                            } elseif ($this->saturday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'no. 16<br>';
                                break 2;
                            }
                            break;
                        case 7: // Sunday
                            if ($this->monday) {
                                $next_date->addDays(1);
                            } elseif ($this->tuesday) {
                                $next_date->addDays(2);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(3);
                            } elseif ($this->thursday) {
                                $next_date->addDays(4);
                            } elseif ($this->friday) {
                                $next_date->addDays(5);
                            } elseif ($this->saturday) {
                                $next_date->addDays(6);
                            } elseif ($this->sunday) {
                                $next_date->addDays(7);
                            } else {
                                // echo 'no. 15<br>';
                                break 2;
                            }
                            break;
                        default:
                            // echo 'no. 14<br>';
                            break 2;
                    }
                } elseif ($this->monthly_frequency) {
                    switch ($last_date->dayOfWeekIso) {
                        case 1: // Monday
                            if ($this->tuesday) {
                                $next_date->addDays(1);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(2);
                            } elseif ($this->thursday) {
                                $next_date->addDays(3);
                            } elseif ($this->friday) {
                                $next_date->addDays(4);
                            } elseif ($this->saturday) {
                                $next_date->addDays(5);
                            } elseif ($this->sunday) {
                                $next_date->addDays(6);
                            } elseif ($this->monday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 13<br>';
                                break 2;
                            }
                            break;
                        case 2: // tuesday
                            if ($this->wednesday) {
                                $next_date->addDays(1);
                            } elseif ($this->thursday) {
                                $next_date->addDays(2);
                            } elseif ($this->friday) {
                                $next_date->addDays(3);
                            } elseif ($this->saturday) {
                                $next_date->addDays(4);
                            } elseif ($this->sunday) {
                                $next_date->addDays(5);
                            } elseif ($this->monday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 12<br>';
                                break 2;
                            }
                            break;
                        case 3: // wednesday
                            if ($this->thursday) {
                                $next_date->addDays(1);
                            } elseif ($this->friday) {
                                $next_date->addDays(2);
                            } elseif ($this->saturday) {
                                $next_date->addDays(3);
                            } elseif ($this->sunday) {
                                $next_date->addDays(4);
                            } elseif ($this->monday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 11<br>';
                                break 2;
                            }
                            break;
                        case 4: // Thursday
                            if ($this->friday) {
                                $next_date->addDays(1);
                            } elseif ($this->saturday) {
                                $next_date->addDays(2);
                            } elseif ($this->sunday) {
                                $next_date->addDays(3);
                            } elseif ($this->monday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'nr. 10';
                                return;
                            }
                            break;
                        case 5: // Friday
                            if ($this->saturday) {
                                $next_date->addDays(1);
                            } elseif ($this->sunday) {
                                $next_date->addDays(2);
                            } elseif ($this->monday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } elseif ($this->friday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(5 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 10<br>';
                                break 2;
                            }
                            break;
                        case 6: // Saturday
                            if ($this->sunday) {
                                $next_date->addDays(1);
                            } elseif ($this->monday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } elseif ($this->friday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(5 - $next_date->dayOfWeekIso);
                            } elseif ($this->saturday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(6 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 9<br>';
                                break 2;
                            }
                            break;
                        case 7: // Sunday
                            if ($this->monday) {
                                $next_date->addMoths(1);
                            } elseif ($this->tuesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->friday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } elseif ($this->saturday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(5 - $next_date->dayOfWeekIso);
                            } elseif ($this->saturday) {
                                $next_date->addMonths(1);
                                $next_date->addDays(6 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 8<br>';
                                break 2;
                            }
                            break;
                        default:
                            // echo 'no. 7<br>';
                            break 2;
                    }
                } else {
                    switch ($last_date->dayOfWeekIso) {
                        case 1: // Monday
                            if ($this->tuesday) {
                                $next_date->addDays(1);
                            } elseif ($this->wednesday) {
                                $next_date->addDays(2);
                            } elseif ($this->thursday) {
                                $next_date->addDays(3);
                            } elseif ($this->friday) {
                                $next_date->addDays(4);
                            } elseif ($this->saturday) {
                                $next_date->addDays(5);
                            } elseif ($this->sunday) {
                                $next_date->addDays(6);
                            } elseif ($this->monday) {
                                $next_date->addYears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 6<br>';
                                break 2;
                            }
                            break;
                        case 2: // tuesday
                            if ($this->wednesday) {
                                $next_date->addDays(1);
                            } elseif ($this->thursday) {
                                $next_date->addDays(2);
                            } elseif ($this->friday) {
                                $next_date->addDays(3);
                            } elseif ($this->saturday) {
                                $next_date->addDays(4);
                            } elseif ($this->sunday) {
                                $next_date->addDays(5);
                            } elseif ($this->monday) {
                                $next_date->addYears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 5<br>';
                                break 2;
                            }
                            break;
                        case 3: // wednesday
                            if ($this->thursday) {
                                $next_date->addDays(1);
                            } elseif ($this->friday) {
                                $next_date->addDays(2);
                            } elseif ($this->saturday) {
                                $next_date->addDays(3);
                            } elseif ($this->sunday) {
                                $next_date->addDays(4);
                            } elseif ($this->monday) {
                                $next_date->addYears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 4<br>';
                                break 2;
                            }
                            break;
                        case 4: // Thursday
                            if ($this->friday) {
                                $next_date->addDays(1);
                            } elseif ($this->saturday) {
                                $next_date->addDays(2);
                            } elseif ($this->sunday) {
                                $next_date->addDays(3);
                            } elseif ($this->monday) {
                                $next_date->addYears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addYears(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'nr. 9';
                                return;
                            }
                            break;
                        case 5: // Friday
                            if ($this->saturday) {
                                $next_date->addDays(1);
                            } elseif ($this->sunday) {
                                $next_date->addDays(2);
                            } elseif ($this->monday) {
                                $next_date->addyears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addYears(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addyears(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } elseif ($this->friday) {
                                $next_date->addyears(1);
                                $next_date->addDays(5 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 3<br>';
                                break 2;
                            }
                            break;
                        case 6: // Saturday
                            if ($this->sunday) {
                                $next_date->addDays(1);
                            } elseif ($this->monday) {
                                $next_date->addyears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->tuesday) {
                                $next_date->addyears(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addyears(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addyears(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } elseif ($this->friday) {
                                $next_date->addyears(1);
                                $next_date->addDays(5 - $next_date->dayOfWeekIso);
                            } elseif ($this->saturday) {
                                $next_date->addyears(1);
                                $next_date->addDays(6 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 2<br>';
                                break 2;
                            }
                            break;
                        case 7: // Sunday
                            if ($this->monday) {
                                $next_date->addDays(1);
                            } elseif ($this->tuesday) {
                                $next_date->addyears(1);
                                $next_date->addDays(1 - $next_date->dayOfWeekIso);
                            } elseif ($this->wednesday) {
                                $next_date->addyears(1);
                                $next_date->addDays(2 - $next_date->dayOfWeekIso);
                            } elseif ($this->thursday) {
                                $next_date->addyears(1);
                                $next_date->addDays(3 - $next_date->dayOfWeekIso);
                            } elseif ($this->friday) {
                                $next_date->addyears(1);
                                $next_date->addDays(4 - $next_date->dayOfWeekIso);
                            } elseif ($this->saturday) {
                                $next_date->addyears(1);
                                $next_date->addDays(5 - $next_date->dayOfWeekIso);
                            } elseif ($this->saturday) {
                                $next_date->addyears(1);
                                $next_date->addDays(6 - $next_date->dayOfWeekIso);
                            } else {
                                // echo 'no. 1<br>';
                                break 2;
                            }
                            break;
                        default:
                            // echo 'no. 0<br>';
                            break 2;
                    }
                }

            }

            // echo ($next_date <= $endDate) . "<br>";
        }
        return $count;
    }

    /**
     * Returns the to time
     * @return null|static
     */
    public function getTo(){
        if(isset($this->to)){
            return Carbon::createFromTimeString($this->to, 'Europe/Berlin');
        } else {
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
    public function getfrom(){
        if(isset($this->from)){
            return Carbon::createFromTimeString($this->from, 'Europe/Berlin');
        } else {
            return null;
        }
    }

    /**
     * Returns from time as a formated String
     * @return string
     */
    public function getFromStr(){
        $fromTime = $this->getFrom();
        return (isset($fromTime)) ? $fromTime->format('H:i') : '';
    }

    /**
     * Returns the Start Date from which the recurring Reservations start
     * @return Carbon|null
     */
    public function getStartDate(){
        if(isset($this->start_date)){
            return new Carbon($this->start_date);
        } else {
            return null;
        }
    }

    /**
     * Return the start date in a formated String
     * @return string
     */
    public function getStartDateStr(){
        $startDate = $this->getStartDate();
        return (isset($startDate)) ? $startDate->format('Y-m-d') : '';
    }

    /**
     * Returns the End date until which the recurring reservation will go
     * @return Carbon|null
     */
    public function getEndDate(){
        if(isset($this->end_date)){
            return new Carbon($this->end_date);
        } else {
            return null;
        }
    }

    /**
     * Return the start date in a formated String
     * @return string
     */
    public function getEndDateStr(){
        $startDate = $this->getEndDate();
        return (isset($startDate)) ? $startDate->format('Y-m-d') : '';
    }

    /**
     * Deletes Template and all reservations based on it
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        foreach($this->reservations as $reservation){
            $reservation->delete();
        }
        return parent::delete();
    }

    /**
     * Returns the String for the current Priority
     * @return array|null|string
     */
    public function getPriority(){
        switch($this->priority){
            case Reservation::PRIORITY_FLEXIBLE:
                return __('messages.flexible');
                break;
            case Reservation::PRIORITY_HIGH:
                return __('messages.high');
                break;
            case Reservation::PRIORITY_MIDDLE:
                return __('messages.middle');
                break;
            case Reservation::PRIORITY_LOW:
                return __('messages.low');
                break;
            default:
                return __('messages.unknown');
                break;
        }
    }

    /**
     * Returns the first Date for the recurring reservations
     * @return mixed
     */
    public function getFirstDate(){
        $first = $this->reservations()->where('deleted',false)->orderBy('date','asc')->first();
        return $first->getDateStr();
    }

    /**
     * Returns the relavent reservations
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
