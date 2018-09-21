@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('messages.reservation') }}
                        <a href="{{ route('reservations.edit',['id' => $reservation->id]) }}" class="btn btn-outline-primary">
                            <span class="oi oi-pencil"></span>
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>{{ __('messages.shared-object') }}</td>
                                    <td>{{ $reservation->sharedObject->designation }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.user') }}</td>
                                    <td>{{ $reservation->user->username }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.period') }}</td>
                                    <td>{{ __('messages.date-from-to', ['DATE'=>$reservation->getDateStr(),'FROM'=>$reservation->getFromStr(),'TO'=>$reservation->getToStr()]) }}</td>
                                </tr>
                                @if(strlen($reservation->reason) > 0)
                                    <tr>
                                        <td>{{ __('messages.reason') }}</td>
                                        <td>{{ $reservation->reason }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ __('messages.priority') }}</td>
                                    <td>
                                        @if($reservation->priority == \App\Reservation::PRIORITY_HIGH)
                                            {{ __('messages.high') }}
                                        @elseif($reservation->priority == \App\Reservation::PRIORITY_FLEXIBLE)
                                            {{ __('messages.flexible') }}
                                        @elseif($reservation->priority == \App\Reservation::PRIORITY_MIDDLE)
                                            {{ __('messages.middle') }}
                                        @else
                                            {{ __('messages.low') }}
                                        @endif
                                    </td>
                                </tr>
                                @if($reservation->type == \App\Reservation::TYPE_REPEATING)
                                    <tr>
                                        <td>{{ __('messages.manuel') }}</td>
                                        <td>
                                            @if($reservation->type)
                                                {{ __('messages.preset') }}
                                            @else
                                                {{ __('messages.manuel-set') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($reservation->conflicts()->count() > 0)
                    <div class="card mt-2">
                        <div class="card-header">
                            {{ __('messages.conflicts') }}
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.user') }}</th>
                                    <th>{{ __('messages.priority')  }}</th>
                                    <th>{{ __('messages.period')  }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reservation->conflicts() as $conflict)
                                    <tr>
                                        <td>{{ $conflict->user->username }}</td>
                                        <td>{{ $conflict->getPriority() }}</td>
                                        <td>{{ __('messages.date-from-to', ['DATE' => $conflict->getDateStr(), 'FROM' => $conflict->getFromStr(), 'TO' => $conflict->getToStr()]) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection