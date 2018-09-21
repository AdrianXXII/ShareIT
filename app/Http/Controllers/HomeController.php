<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
