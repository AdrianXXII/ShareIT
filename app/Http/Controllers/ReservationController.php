<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Reservation;
use App\ReservationTemplate;
use App\SharedObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $user = Auth::user();
        $fromDate = null;
        $toDate = null;
        $sharedObject = null;
        $sharedObjects = $user->sharedObjects;
        $reservations = $user->reservations()->where('deleted',false)->orderBy('date','asc')->orderBy('from','asc')->orderBy('to','asc')->get();
        $templates = $user->templates()->orderBy('start_date','asc')->orderBy('end_date','asc')->get();
        return view('reservations.index',compact(['reservations','templates','fromDate','toDate','sharedObject','sharedObjects']));
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
        $frequencies = null;
        $datebased = $request->input('reservation-is-date-based') == 'true'?true:false;
        if($datebased){
            $frequencies = ['monthly','yearly'];
        }
        else {
            $frequencies = ['weekly','monthly','yearly'];
        }
        $validatedData = $request->validate([
                'shared_object_id' => 'required|Integer',
                'reservation-type' => 'required|Integer',
                'priority' => 'required|Integer',
                'reason' => 'string|nullable|min:2|max:250',
                'reservation-date' => 'required|date|after_or_equal:today',
                'reservation-from' => 'required',
                'reservation-to' => 'required',
                'reservation-is-date-based' => 'required_if:reservation-type,2',
                'reservation-frequency' => [
                    'required_if:reservation-is-date-based,false',
                    Rule::in($frequencies)
                ],
                'reservation-days' => 'required_if:reservation-is-date-based,false',
                'reservation-start-date' => 'required_if:reservation-type,2|nullable|date',
                'reservation-end-date' => 'required_if:reservation-type,2|nullable|date',
            ],
            [
                'shared_object_id.required' => 'No Shared Object',
                'shared_object_id.integer' => 'No Shared Object',
                'reservation-type.required' => 'No reservation type availbable',
                'reservation-type.integer' => 'Not a real type',
                'reservation-date.required' => 'No Date',
                'reservation-date.date' => 'Not a date',
                'reservation-date.after_or_equal' => 'Date in the past',
                'reservation-from.required' => 'From time missing',
                'reservation-to.required' => 'To time missing',
                'reservation-is-date-based.required_if' => 'Required if type recurring',
                'reservation-frequency.required_if' => 'Frequency is missing',
                'reservation-frequency.in' => 'Not a valid frequency',
                'reservation-days.required_if' => 'No days given, despite this not being a date based recurring reservation.',
                'reservation-start-date.required_if' => 'No start date given, despite this being a recurring reservation.',
                'reservation-start-date.date' => 'Start date given, is not a actual date.',
                'reservation-end-date.required_if' => 'No end date given, despite this being a recurring reservation.',
                'reservation-end-date.date' => 'End date given, is not a actual date.',
            ]);

        //
        $sharedObject = SharedObject::find($request->input('shared_object_id'));
        $user = Auth::user();
        $type = Reservation::checkValidType($request->input('reservation-type'));
        $date  = new Carbon($request->input('reservation-date'));
        $priority = Reservation::checkValidPriority($request->input('priority'));
        $from = Carbon::createFromTimeString($request->input('reservation-from'));
        $to = Carbon::createFromTimeString($request->input('reservation-to'));

        if($type == Reservation::TYPE_REPEATING) {
            $monday = false;
            $tuesday = false;
            $wednesday = false;
            $thursday = false;
            $friday = false;
            $saturday = false;
            $sunday = false;
            $weekly = false;
            $monthly = false;
            $yearly = false;

            switch ($request->input('reservation-frequency')) {
                case 'weekly':
                    if(!$datebased){
                        $weekly = true;
                    }
                    break;
                case 'monthly':
                    $monthly = true;
                    break;
                case 'yearly':
                    $yearly = true;
                    break;
                default:
                    $yearly = true;
                    break;
            }

            if(!$datebased){
                foreach ($request->input('reservation-days') as $day) {
                    switch ($day) {
                        case 'monday':
                            $monday = true;
                            break;
                        case 'tuesday':
                            $tuesday = true;
                            break;
                        case 'wednesday':
                            $wednesday = true;
                            break;
                        case 'thursday':
                            $thursday = true;
                            break;
                        case 'friday':
                            $friday = true;
                            break;
                        case 'saturday':
                            $saturday = true;
                            break;
                        case 'sunday':
                            $sunday = true;
                            break;
                    }
                }
            }


            $template = new ReservationTemplate();
            $template->date = $date->day;
            $template->month = $date->month;
            $template->weekly_frequency = $weekly;
            $template->monthly_frequency = $monthly;
            $template->yearly_frequency = $yearly;
            $template->is_day_based = $datebased;
            $template->monday = $monday;
            $template->tueday = $tuesday;
            $template->wednesday = $wednesday;
            $template->thursday = $thursday;
            $template->friday = $friday;
            $template->saturday = $saturday;
            $template->sunday = $sunday;
            $template->priority = $priority;
            $template->from = $from;
            $template->to = $to;
            $template->reason = $request->input('reason');
            $template->start_date = $request->input('reservation-start-date');
            $template->end_date = $request->input('reservation-end-date');
            $template->user()->associate($user);
            $template->sharedObject()->associate($sharedObject);

            $template->save();

            foreach($template->reservations as $reservation){
                if($reservation->conflics != null && $reservation->conflicts->count()){
                    Notification::personalConflictNotifications($user,$reservation);
                }
            }

            Notification::templateCreated($user,$template);
            Notification::templatePersonalConflictNotification($user,$template);

        }
        else {
            $reservation = new Reservation();
            $reservation->type = $type;
            $reservation->priority = $priority;
            $reservation->manuel = true;
            $reservation->date = $date;
            $reservation->from = $from;
            $reservation->to = $to;
            $reservation->reason = $request->input('reason');
            $reservation->deleted = false;

            $reservation->user()->associate($user);
            $reservation->sharedObject()->associate($sharedObject);
            $reservation->save();


            Notification::personalConflictNotifications($user,$reservation);
            Notification::reservationCreated($user, $reservation);
        }

        return redirect(route('reservations.index'));
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
        $user = Auth::user();
        $reservation = Reservation::find($id);
        if($reservation == null || $reservation->delete || $reservation->user->id != $user->id){
            session()->flash("warning", __('messages.reservation-not-available'));
            return redirect(route('reservations.index'));
        }
        return view('reservations.show', compact(['reservation']));
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

        if($reservation == null || $reservation->delete || $reservation->user->id != Auth::id()){
            session()->flash("warning", __('messages.reservation-not-available'));
            return redirect(route('reservations.index'));
        }

        $sharedObject = $reservation->sharedObject();
        return view('reservations.edit', compact(['sharedObject','reservation']));
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
            'priority' => 'required|Integer',
            'reason' => 'string|nullable|min:2|max:250',
            'reservation-date' => 'required|date',
            'reservation-from' => 'required',
            'reservation-to' => 'required',
        ],[
            'reservation-date.required' => 'No Date',
            'reservation-date.date' => 'Not a date',
            'reservation-date.after_or_equal' => 'Date in the past',
            'reservation-from.required' => 'From time missing',
            'reservation-to.required' => 'To time missing'
        ]);

        //
        $date  = new Carbon($request->input('reservation-date'));
        $from = Carbon::createFromTimeString($request->input('reservation-from'));
        $to = Carbon::createFromTimeString($request->input('reservation-to'));

        $reservation = Reservation::find($id);

        if($reservation == null || $reservation->delete || $reservation->user->id != Auth::id()){
            session()->flash("warning", __('messages.reservation-not-available'));
            return redirect(route('reservations.index'));
        }

        $reservation->reason = $request->input('reason');
        $reservation->priority = Reservation::checkValidPriority($request->input('priority'));
        $reservation->manuel = true;
        $reservation->date = $date;
        $reservation->from = $from;
        $reservation->to = $to;
        $reservation->save();

        Notification::personalConflictNotifications(Auth::user(), $reservation);
        Notification::reservationUpdated(Auth::user(), $reservation);

        return redirect(route('reservations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);
        if($reservation == null || $reservation->delete || $reservation->user->id != Auth::id()){
            session()->flash("warning", __('messages.reservation-not-available'));
            return redirect(route('reservations.index'));
        }
        if($reservation->type == Reservation::TYPE_REPEATING){
            $reservation->deleted = true;
            $reservation->conflictingRight()->detach();
            $reservation->conflictingLeft()->detach();
            $reservation->save();
        }
        else {
            $reservation->delete();
        }
        return redirect(route('reservations.index'));
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');
        $sharedObject = $request->get('sharedObject');

        $queryRes = $user->reservations();
        $queryTem = $user->templates();
        if(isset($sharedObject)){
            $queryRes = $queryRes->where('shared_object_id', $sharedObject);
            $queryTem = $queryTem->where('shared_object_id', $sharedObject);
        }
        if(isset($fromDate) && isset($toDate)) {
            $queryRes = $queryRes->whereRaw('date between ? AND ?',
                [$fromDate, $toDate]
            );
            $queryTem = $queryTem->whereRaw('(? between `start_date` AND `end_date` OR ? between `start_date` AND `end_date` OR `start_date` between ? AND ? OR `end_date` between ? AND ?)',
                [$fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate]
            );
        }
        elseif(isset($fromDate)) {
            $queryRes = $queryRes->where('date','>=',$fromDate);
            $queryTem = $queryTem->whereraw('(start_date >= ? OR end_date >= ?)',
                [$fromDate, $fromDate]);
        }
        elseif(isset($toDate)) {
            $queryRes = $queryRes->where('date', '<=', $toDate);
            $queryTem = $queryTem->whereraw('(start_date <= ? OR end_date <= ?)',
                [$toDate, $toDate]);

        }

        $reservations = $queryRes->orderBy('date','asc')
            ->orderBy('from','asc')
            ->orderBy('to','asc')
            ->get();
        $templates = $queryTem->orderBy('start_date','asc')
            ->orderBy('end_date','desc')
            ->get();
        $sharedObjects = $user->sharedObjects;
        // return [$user,$fromDate,  $toDate, $sharedObject, $sharedObjects];
        return view('reservations.index',compact(['reservations', 'templates', 'fromDate', 'toDate', 'sharedObject', 'sharedObjects']));
    }
}
