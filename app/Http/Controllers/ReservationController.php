<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\SharedObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $user = Auth::user();
        $sharedObject = null;
        $sharedObjects = null;

        if(is_null($id)){
            $sharedObjects = $user->sharedObjects;
        } else {
            $sharedObject = SharedObject::find($id);
        }



        return view('reservations.create', compact(['sharedObject','sharedObjects']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'shared_object_id' => 'required|Integer',
            'reservation-type' => 'required|Integer',
            'priority' => 'required|Integer',
            'reservation-date' => 'required|date|after_or_equal:today',
            'reservation-from' => 'required',
            'reservation-to' => 'required',
            'reason' => 'string|nullable|min:2|max:250'
        ]);
        //
        $sharedObject = SharedObject::find($request->input('shared-object'));
        $user = Auth::user();
        $type = $request->input('reservation-type');
        $date  = new Carbon($request->input('reservation-date'));

        $reservation = new Reservation();
        $reservation->type = Reservation::checkValidType($type);
        $reservation->priority = Reservation::checkValidPriority($request->input('priority'));
        $reservation->manuel = true;
        $reservation->date = $date;
        $reservation->from = $request->input('reservation-from');
        $reservation->to = $request->input('reservation-to');
        $reservation->reason = $request->input('reason');
        $reservation->deleted = false;
        $reservation->user()->associate($user);
        $reservation->sharedObject()->associate($sharedObject);

        if($reservation->type = Reservation::TYPE_REPEATING) {
            $template = new ReservationTemplate();
            $template->date = $date->day;
            $template->month = $date->month;
            $template->weekly_frequency = ($request->input('weekly_frequency')==1)? true : false;
            $template->monthly_frequency = ($request->input('monthly_frequency')==1)?true : false;
            $template->yearly_frequency = ($request->input('yearly_frequency')==1)? true : false;
            $template->is_day_based = ($request->input('is_day_based')==1)? true : false;
            $template->monday = ($request->input('monday')==1)? true : false;
            $template->tueday = ($request->input('tueday')==1)? true : false;
            $template->wednesday = ($request->input('wednesday')==1)? true : false;
            $template->thursday = ($request->input('thursday')==1)? true : false;
            $template->friday = ($request->input('friday')==1)? true : false;
            $template->saturday = ($request->input('saturday')==1)? true : false;
            $template->sunday = ($request->input('sunday')==1)? true : false;
            $template->priority = $reservation->priority;
            $template->from = $reservation->from;
            $template->to = $reservation->to;
            $template->start_date = $request->input('start_date');
            $template->end_date = $request->input('end_date');
            $template->save();
            $reservation->template()->associate($template);
        }

        $reservation->save();

        return $reservation;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $reservation = Reservation::find($id);
        return view('reservations.show', compact(['sharedObject']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $reservation = Reservation::find($id);
        return view('reservations.edit', compact(['sharedObject']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validatedData = $request->validate([
            'reservation-type' => 'required|Integer',
            'priority' => 'required|Integer',
            'reservation-date' => 'required|date|after_or_equal:today',
            'reservation-from' => 'required',
            'reservation-to' => 'required',
            'reason' => 'string|nullable|min:2|max:250'
        ]);

        //
        $user = Auth::user();
        $date  = new Carbon($request->input('reservation-date'));

        $reservation = Reservation::find($id);
        $reservation->priority = Reservation::checkValidPriority($request->input('priority'));
        $reservation->manuel = true;
        $reservation->date = $date;
        $reservation->from = $request->input('reservation-from');
        $reservation->to = $request->input('reservation-to');
        $reservation->reason = $request->input('reason');
        $reservation->deleted = false;
        $reservation->save();

        return $reservation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $reservation = Reservation::find($id);
        if($reservation->type = Reservation::TYPE_REPEATING){
            $reservation->deleted = true;
            $reservation->save();
        } else {
            $reservation->delete();
        }
        return redirect(route('home'));
    }
}
