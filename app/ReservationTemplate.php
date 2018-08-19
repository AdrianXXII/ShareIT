<?php

namespace App;

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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sharedObject(){
        return $this->belongsTo(User::class);
    }
}
