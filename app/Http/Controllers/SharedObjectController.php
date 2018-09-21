<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Reservation;
use App\SharedObject;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SharedObjectController extends Controller
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
        $user = \Auth::user();
        $sharedObjects = $user->sharedObjects;

        return view('sharedObjects.index',compact('sharedObjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('sharedObjects.create');
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
            'designation' => 'required|min:2|max:50',
            'description' => 'required',
        ]);

        $user = \Auth::user();

        // Create Shared Object
        $sharedObject = new SharedObject();
        $sharedObject->designation = $request->get('designation');

        $sharedObject->description = $request->get('description');
        $sharedObject->created_at = new Carbon();
        $sharedObject->updated_at = new Carbon();
        $sharedObject->createdBy()->associate($user);
        $sharedObject->updatedBy()->associate($user);
        $sharedObject->save();
        if(!$sharedObject->addUser($user)){
            $request->session()->flash("warning",__('message.not-added-user', ['USERNAME' => $newUser->username]));
        }
        return redirect(route('sharedObjects.index'));
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
        $sharedObject = SharedObject::find($id);

        if($sharedObject == null || !$sharedObject->hasUser(Auth::user())){
            session()->flash('warning',__('messages.invalid-action'));
            return redirect(route('home'));
        }

        $users = User::all();
        return view('sharedObjects.show', compact(['sharedObject','users']));
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
        $sharedObject = SharedObject::find($id);

        if($sharedObject == null || !$sharedObject->hasUser(Auth::user())){
            session()->flash('warning',__('messages.invalid-action'));
            return redirect(route('home'));
        }

        return view('sharedObjects.edit', compact('sharedObject'));
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
            'designation' => 'required|min:2|max:50',
            'description' => 'required',
        ]);

        $user = \Auth::user();

        $sharedObject = SharedObject::find($id);
        if($sharedObject == null || !$sharedObject->users()->contains($user)){
            session()->flash('warning',__('messages.invalid-action'));
            return redirect(route('home'));
        }

        if($sharedObject->hasUser($user)) {
            $sharedObject->designation = $request->get('designation');
            $sharedObject->description = $request->get('description');
            $sharedObject->updated_at = new Carbon();
            $sharedObject->updatedBy()->associate($user);
            $sharedObject->save();
            $request->session()->flash('success', __('message.object-updated'));
            return redirect(route('sharedObjects.index'));
        } else {
            $request->session()->flash('fail', __('message.object-not-shared-with-you'));
            return redirect(route('sharedObjects.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = \Auth::user();

        $sharedObject = SharedObject::find($id);

        if($sharedObject->hasUser($user) > 0) {
            // $sharedObject->delete();
        } else {

        }
        //
    }

    public function addUser(Request $request, $id){
        $user = \Auth::user();
        $newUser = User::find($request->get('new-user'));
        $sharedObject = SharedObject::find($id);

        if($sharedObject->hasUser($user)) {
            if(!$sharedObject->addUser($newUser)){
                $request->session()->flash("warning",__('messages.not-added-user', ['USERNAME' => $newUser->username]));
            }
        }
        Notification::shareWithUserNotification($newUser,$user,$sharedObject);
        $users = User::all();
        return redirect(route('sharedObjects.show',['id' => $id]));

    }

    public function removeUser(Request $request, $id, $userId)
    {
        $user = \Auth::user();
        $newUser = User::find($userId);
        $sharedObject = SharedObject::find($id);

        if ($sharedObject->hasUser($user)) {
            if(!$sharedObject->removeUser($newUser)) {
                $request->session()->flash("warning", __('messages.not-removed-user', ['USERNAME' => $newUser->username]) );
            }
        }

        Notification::removeUserFromSharedObjectNotification($newUser,$user,$sharedObject);
        $users = User::all();
        return redirect(route('sharedObjects.show',['id' => $id]));

    }

    public function myExport($id)
    {
        $user = Auth::user();
        $reservations = Reservation::where('shared_object_id',$id)
            ->where('user_id',$user->id)
            ->where('deleted',false)
            ->orderBy('date','asc')->get();
        return response()
            ->view('ical.events',compact('reservations'))
            ->header('Content-Type', 'text/calendar');
    }

    public function objectExport($id)
    {
        $sharedObject = SharedObject::find($id);

        if($sharedObject == null || !$sharedObject->hasUser(Auth::user())){
            session()->flash('warning',__('messages.invalid-action'));
            return redirect(route('home'));
        }

        $reservations = $sharedObject->getReleventReservations();
        return response()
            ->view('ical.events',compact('reservations'))
            ->header('Content-Type', 'text/calendar');
    }
}
