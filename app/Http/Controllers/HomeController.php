<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function myExport(){
        $user = Auth::user();
        $reservations = $user->getRelaventReservations();
        return response()
            ->view('ical.events',compact('reservations'))
            ->header('Content-Type', 'text/calendar');
    }

    public function mailingTest(){
        try {
            $notifications = Notification::pending();
            foreach($notifications as $notification)
            {
                $notification->status = Notification::STATUS_SENDING;
                $notification->save();
            }

            foreach($notifications as $notification)
            {
                Mail::to($notification->email)
                    ->send(new NotificationMail($notification));
                if(count(Mail::failures()) <= 0){
                    $notification->status = Notification::STATUS_SUCCESS;
                }
                elseif($notification->getCreationDate()->diffInDays(Carbon::now) > 7){
                    $notification->status = Notification::STATUS_GIVEN_UP;
                }
                else {
                    $notification->status = Notification::STATUS_FAILED;
                }
                $notification->save();
            }
        } catch(Exception $e) {
            $notification->status = Notification::STATUS_FAILED;
            $notification->save();
        }
    }
}
