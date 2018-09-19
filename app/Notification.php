<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
    protected $fillable = ['email','subject','content','status',];


    public function getCreationDate(){
        return new Carbon($this->created);
    }
    public static function pending()
    {
        return self::where('status',self::STATUS_PENDING)->get();
    }

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

    public static function personalConflictNotifications(User $user, Reservation $reservation){
        $conflicts = $reservation->conflicts();
        if( $conflicts->count() <= 0) {
            return;
        }
        $conflicts->sortby('from');
        $affectedUsers = new Collection();
        $content = _('message.conflicting-reservations-personal-content');
        $content += __('message.conflicting-personal-reservations-content2', [
            'SHARED_OBJECT' => $reservation->sharedObject->designation
        ]);
        foreach($conflicts as $conflict){
            if(!$affectedUsers->contains($conflict->user)){
                $affectedUsers->add($user);
            }
            $content += __(
                'messages.conflicting-reservations-content3', [
                    'USERNAME' => $conflict->user->username,
                    'FROM' => $conflict->getFromStr(),
                    'TO' => $conflict->getToStr(),
                    'MY_FROM' => $reservation->getFromStr(),
                    'MY_TO' => $reservation->getToStr()
                ]);
        }

        $notification = new Notification();
        $notification->email = $user->email;
        $notification->subject = __('message.conflicting-reservations-personal-subject');
        $notification->content = $content;
        $notification->status = self::STATUS_PENDING;
        $notification->save();

        self::conflictNotifications($user,$affectedUsers, $reservation, $conflicts);

    }

    public static function conflictNotifications(User $reserver, Collection $users, Reservation $reservation, $conflicts){
        foreach($users as $user){
            $content = __('messages.conflicting-reservations-recipient-content',[
                    'USERNAME' => $reserver->username,
                    'FROM' => $reservation->getFromStr(),
                    'TO' => $reservation->getToStr(),
                ]);
            foreach($conflicts as $conflict){
                if($conflict->user == $user){
                    $content += __('messages.conflicting-reservations-recipient-content2',[
                        'FROM' => $conflict->getFromStr(),
                        'TO' => $conflict->getToStr()
                    ]);
                }
            }

            $notification = new Notification();
            $notification->email = $user->email;
            $notification->subject = __('messages.conflicting-reservations-recipient-subject',[
                'USERNAME' => $reserver->username
            ]);
            $notification->content = $content;
            $notification->status = self::STATUS_PENDING;
            $notification->save();

        }

    }

    public static function templatePersonalConflictNotification(User $user, ReservationTemplate $template){
        $content = __('messages.conflicting-reservations-template-personal-content',[
            'SHARED_OBJECT' => $template->sharedObject->designation
        ]);
        $affectedUsers = new Collection();
        foreach($template->reservations as $reservation){
            $conflicts = $reservation->conflicts();
            if($content->count() <= 0){
                continue;
            }
            $conflicts->sortBy('from');
            $content += __('messages.conflicting-reservations-template-personal-content2',[
                'DATE' => $reservation->getDateStr(),
                'FROM' => $reservation->getFromStr(),
                'TO' => $reservation->getToStr(),
            ]);

            foreach($conflicts as $conflict){
                if(!$affectedUsers->contains($conflict->user)){
                    $affectedUsers->add($conflict->user);
                }

                $content += __('messages.conflicting-reservations-template-personal-content2',[
                    'USERNAME' => $conflict->user->username,
                    'DATE' => $conflict->date,
                    'FROM' => $conflict->getFromStr(),
                    'TO' => $conflict->getToStr(),
                ]);
            }
            $content += _('messages.conflicting-reservations-new-line');
        }

        $notification = new Notification();
        $notification->email = $user->email;
        $notification->subject = __('messages.conflicting-reservations-template-personal-subject');
        $notification->content = $content;
        $notification->status = self::STATUS_PENDING;
        $notification->save();
    }

    public static function templateConflictReservation(Collection $users, $template){
        $userContent = array();
        foreach($template->reservations as $reservation){
            foreach($reservation->conflicts() as $conflict){
                $userContent[$conflict->user->username] += __('messages.conflicting-reservations-template-recipient-content2', [
                    'DATE' => $conflict->getDateStr(),
                    'FROM' => $conflict->getFromStr(),
                    'FROM2' => $reservation->getFromStr(),
                    'TO' => $conflict->getToStr(),
                    'TO2' => $reservation->getToStr()
                ]);
            }
        }
        foreach($users as $user){
            $content = __('messages.conflicting-reservations-template-recipient-content',[
                'USERNAME' => $user->username
            ]);

            $content += $userContent[$user->username];

            $notification = new Notification();
            $notification->email = $user->email;
            $notification->subject = __('messages.conflicting-reservations-recipient-subject',[
                'USERNAME' => $user->username
            ]);
            $notification->content = $content;
            $notification->status = self::STATUS_PENDING;
            $notification->save();
        }
    }

    public static function templateCreated(User $user, ReservationTemplate $template){
        $frequency = '';
        $days = array();
        $daysOfTheWeek = '';
        if(!$template->is_day_based) {
            if ($template->monday) {
                $days[] = __('messages.monday');
            }
            if ($template->tuesday) {
                $days[] = __('messages.tuesday');
            }
            if ($template->wednesday) {
                $days += __('messages.wednesday');
            }
            if ($template->thursday) {
                $days[] = __('messages.thursday');
            }
            if ($template->friday) {
                $days[] = __('messages.friday');
            }
            if ($template->saturday) {
                $days[] = __('messages.saturday');
            }
            if ($template->sunday) {
                $days[] = __('messages.sunday');
            }
            foreach ($days as $i => $day) {
                if ($i > 1 && count($days)) {
                    $daysOfTheWeek += ', ';

                } elseif ($i == count($days) - 1) {
                    $daysOfTheWeek += ' & ';
                }
                $daysOfTheWeek += $day;
            }
        }

        if($template->yearly_frequency){
            $frequency = __('messages.yearly');
            if($template->is_day_based){
                $frequency += __('messages.frequency-date-yearly-msg',[
                    'DAY' => $template->date,
                    'MONTH' => $template->month
                ]);
            } else {
                $frequency += __('messages.frequency-days-yearly-msg', [
                    'DAY' => $template->date,
                    'MONTH' => $template->month,
                    'DAYS' => $daysOfTheWeek
                ]);
            }
        }
        elseif($template->monthly_frequency) {
            $frequency = __('messages.monthly');
            if($template->is_day_based){
                /*
                 * TODO
                $frequency += __('messages.frequency-date-yearly-msg', [
                    'DAY' => $template->date,
                    'MONTH' => $template->month
                ]);
                */
            } else {
                $frequency += __('messages.frequency-days-yearly-msg',[
                    'DAY' => $template->date,
                    'DAYS' => $daysOfTheWeek
                ]);
            }
        }
        elseif($template->daily_frequency) {
            $frequency = __('messages.frequency-days-daily-msg',[
                'DAYS' => $daysOfTheWeek
            ]);
        }
        /*
         * TODO
        $frequency += __('messages.frequency-start-end-msg',[
            'FROM'=>$template->getFromStr(),
            'TO'=>$template->getToStr(),
            'START'=>$template->getStartDateStr(),
            'END'=>$template->getEndDateStr()
        ]);
*/

        foreach($template->sharedObject->users as $rec){
            if($rec->id != $user->id){
                $content = __('messages.notification-greeting',['USERNAME' => $rec->getFullname()]);
                /*
                * TODO
                $content += __('messages.notification-template-create-content', [
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $template->sharedObject->designation,
                    'FREQUENCY' => $frequency
                ]);
                */
                $notification = new Notification();
                $notification->email = $user->email;
                $notification->subject = __('messages.notification-template-create-subject',[
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $template->sharedObject->designation
                ]);
                $notification->content = $content;
                $notification->status = self::STATUS_PENDING;
                $notification->save();
            }
        }
    }

    public static function templateUpdated(User $user, ReservationTemplate $template){
        $frequency = '';
        $days = array();
        $daysOfTheWeek = '';
        if(!$template->is_day_based) {
            if ($template->monday) {
                $days[] = __('messages.monday');
            }
            if ($template->tuesday) {
                $days[] = __('messages.tuesday');
            }
            if ($template->wednesday) {
                $days += __('messages.wednesday');
            }
            if ($template->thursday) {
                $days[] = __('messages.thursday');
            }
            if ($template->friday) {
                $days[] = __('messages.friday');
            }
            if ($template->saturday) {
                $days[] = __('messages.saturday');
            }
            if ($template->sunday) {
                $days[] = __('messages.sunday');
            }
            foreach ($days as $i => $day) {
                if ($i > 1 && count($days)) {
                    $daysOfTheWeek += ', ';

                } elseif ($i == count($days) - 1) {
                    $daysOfTheWeek += ' & ';
                }
                $daysOfTheWeek += $day;
            }
        }

        if($template->yearly_frequency){
            $frequency = __('messages.yearly');
            if($template->is_day_based){
                $frequency += __('messages.frequency-date-yearly-msg',[
                    'DAY'=>$template->date,
                    'MONTH'=>$template->month
                ]);
            } else {
                $frequency += __('messages.frequency-days-yearly-msg', [
                    'DAY'=>$template->date,
                    'MONTH'=>$template->month,
                    'DAYS'=>$daysOfTheWeek
                ]);
            }
        }
        elseif($template->monthly_frequency) {
            $frequency = __('messages.monthly');
            if($template->is_day_based){
                $frequency += __('messages.frequency-date-yearly-msg',[
                    'DAY'=>$template->date,
                    'MONTH'=>$template->month
                ]);
            } else {
                $frequency += __('messages.frequency-days-yearly-msg',[
                    'DAY'=>$template->date,
                    'DAYS'=>$daysOfTheWeek
                ]);
            }
        }
        elseif($template->daily_frequency) {
            $frequency = __('messages.frequency-days-daily-msg',[
                'DAYS' => $daysOfTheWeek
            ]);
        }

        $frequency += __('messages.frequency-start-end-msg',[
            'FROM'=>$template->getFromStr(),
            'TO'=>$template->getToStr(),
            'START'=>$template->getStartDateStr(),
            'END'=>$template->getEndDateStr()
        ]);


        foreach($template->sharedObject->users as $rec){
            if($rec->id != $user->id){
                $content = __('messages.notification-greeting',['USERNAME' => $rec->getFullname()]);
                $content += __('messages.notification-template-update-content', [
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $template->sharedObject->designation,
                    'FREQUENCY' => $frequency
                ]);
                $notification = new Notification();
                $notification->email = $user->email;
                $notification->subject = __('messages.notification-template-update-subject',[
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $template->sharedObject->designation
                ]);
                $notification->content = $content;
                $notification->status = self::STATUS_PENDING;
                $notification->save();
            }
        }
    }

    public static function reservationCreated(User $user, Reservation $reservation){
        foreach($reservation->sharedObject->users as $rec){
            if($rec->id != $user->id){
                $content = __('messages.notification-greeting',['USERNAME' => $rec->getFullname()]);
                $content += __('messages.notification-reservation-create-content', [
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $reservation->sharedObject->designation,
                    'DATE' => $reservation->getDateStr()
                ]);
                $notification = new Notification();
                $notification->email = $user->email;
                $notification->subject = __('messages.notification-reservation-create-subject',[
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $reservation->sharedObject->designation
                ]);
                $notification->content = $content;
                $notification->status = self::STATUS_PENDING;
                $notification->save();
            }
        }
    }

    public static function reservationUpdated(User $user, Reservation $reservation){
        foreach($reservation->sharedObject->users as $rec){
            if($rec->id != $user->id){
                $content = __('messages.notification-greeting',['USERNAME' => $rec->getFullname()]);
                /*
                 * TODO
                $content += __('messages.notification-reservation-update-content', [
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $reservation->sharedObject->designation,
                    'DATE' => $reservation->getDateStr()
                ]);
                */
                $notification = new Notification();
                $notification->email = $user->email;
                $notification->subject = __('messages.notification-reservation-update-subject',[
                    'CREATOR' => $user->username,
                    'SHARED_OBJECT' => $reservation->sharedObject->designation
                ]);
                $notification->content = $content;
                $notification->status = self::STATUS_PENDING;
                $notification->save();
            }
        }
    }
}