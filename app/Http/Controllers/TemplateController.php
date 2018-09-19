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

class TemplateController extends Controller
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
        return redirect(route('reservations.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        session()->flash("warning", __('messages.reservation-not-available'));
        return redirect(route('templates.index'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->session()->flash("warning",__('message.invalid-url', ['THING' => 'recurring reservation']));
        return redirect( route('home') );
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
        $template = ReservationTemplate::find($id);
        if($template == null || $template->delete || $template->user->id != $user->id){
            session()->flash("warning", __('messages.reservation-not-available'));
            return redirect(route('templates.index'));
        }
        return view('reservationTemplates.show', compact(['template']));
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
        $user = Auth::user();
        $template = ReservationTemplate::find($id);
        $sharedObject = $template->sharedObject();
        if($template == null || $template->delete || $template->user->id != $user->id){
            session()->flash("warning", __('messages.reservation-not-available'));
            return redirect(route('templates.index'));
        }
        return view('reservationTemplates.edit', compact(['sharedObject','template']));
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
        $frequencies = null;
        $datebased = $request->input('reservation-is-date-based') == 'true'?true:false;
        if($datebased){
            $frequencies = ['monthly','yearly'];
        }
        else {
            $frequencies = ['weekly','monthly','yearly'];
        }

        $validatedData = $request->validate([
            'priority' => 'required|Integer',
            'reason' => 'string|nullable|min:2|max:250',
            'reservation-date' => 'required|date|after_or_equal:today',
            'reservation-from' => 'required',
            'reservation-to' => 'required',
            'reservation-is-date-based' => 'required',
            'reservation-frequency' => [
                'required_if:reservation-is-date-based,false',
                Rule::in($frequencies)
            ],
            'reservation-days' => 'required_if:reservation-is-date-based,false',
            'reservation-start-date' => 'required_if:reservation-type,2|nullable|date',
            'reservation-end-date' => 'required_if:reservation-type,2|nullable|date',
        ],
        [
            'reservation-date.date' => 'Not a date',
            'reservation-date.after_or_equal' => 'Date in the past',
            'reservation-from.required' => 'From time missing',
            'reservation-to.required' => 'To time missing',
            'reservation-is-date-based.required' => 'It must be specified, if it\'s date based or not.',
            'reservation-frequency.required' => 'Frequency is missing',
            'reservation-frequency.in' => 'Not a valid frequency',
            'reservation-days.required_if' => 'No days given, despite this not being a date based recurring reservation.',
            'reservation-start-date.required' => 'No start date given.',
            'reservation-start-date.date' => 'Start date given, is not a actual date.',
            'reservation-end-date.required' => 'No end date given.',
            'reservation-end-date.date' => 'End date given, is not a actual date.',
        ]);

        //
        $sharedObject = SharedObject::find($request->input('shared_object_id'));
        $user = Auth::user();
        $date  = new Carbon($request->input('reservation-date'));
        $priority = Reservation::checkValidPriority($request->input('priority'));
        $from = Carbon::createFromTimeString($request->input('reservation-from'));
        $to = Carbon::createFromTimeString($request->input('reservation-to'));

        // Days
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

        $template = ReservationTemplate::find($id);
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
        $template->save();

        foreach($template->reservations as $reservation){
            if($reservation->conflics != null && $reservation->conflicts->count()){
                Notification::personalConflictNotifications($user,$reservation);
            }
        }

        Notification::templateCreated($user,$template);

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

        //
        $reservation = ReservationTemplate::find($id);
        $reservation->delete();
        return redirect(route('home'));
    }
}
