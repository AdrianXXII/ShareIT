@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('messages.reservation-for', ['SHARED_OBJECT' => $reservation->sharedObject->designation]) }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reservations.update', ['id' => $reservation->id]) }}" method="POST" aria-label="{{ __('messages.reservation') }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="reason" class="col-md-4 col-form-label text-md-right">{{ __('messages.reason') }}</label>

                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('reason') ? ' is-invalid' : '' }}" type="text" name="reason" id="reason" value="{{ old('reason', $reservation->reason) }}">

                                    @if ($errors->has('reason'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('reason') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="priority" class="col-md-4 col-form-label text-md-right">{{ __('messages.priority') }}*</label>

                                <div class="col-md-6">
                                    <select name="priority" id="priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}">
                                        <option {{ (old('priority', $reservation->priority) == \App\Reservation::PRIORITY_FLEXIBLE )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_FLEXIBLE }}">{{ __('messages.flexible') }}</option>
                                        <option {{ (old('priority', $reservation->priority) == \App\Reservation::PRIORITY_HIGH )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_HIGH }}">{{ __('messages.high') }}</option>
                                        <option {{ (old('priority', $reservation->priority) == \App\Reservation::PRIORITY_MIDDLE )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_MIDDLE }}">{{ __('messages.middle') }}</option>
                                        <option {{ (old('priority', $reservation->priority) == \App\Reservation::PRIORITY_LOW )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_LOW }}">{{ __('messages.low') }}</option>
                                    </select>

                                    @if ($errors->has('priority'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('priority') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-type" class="col-md-4 col-form-label text-md-right">{{ __('messages.type') }}</label>

                                <div class="col-md-6">
                                    <p>
                                        {{ __('messages.one-time') }}
                                    </p>

                                    @if ($errors->has('reservation-type'))
                                        <p class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-type') }}</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-date" class="col-md-4 col-form-label text-md-right">{{ __('messages.date') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group date{{ $errors->has('reservation-date') ? ' is-invalid' : '' }}" id="reservation-date" data-target-input="nearest">
                                        <input type="text" name="reservation-date" class="form-control datetimepicker-input" data-target="#reservation-date" value="{{ old('reservation-date', $reservation->getDateStr()) }}">
                                        <div class="input-group-append" data-target="#reservation-date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="oi oi-calendar"></i></div>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-date'))
                                        <p class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-date') }}</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-from" class="col-md-4 col-form-label text-md-right">{{ __('messages.from') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group" id="reservation-from" data-target-input="nearest">
                                        <input type="text" name="reservation-from" class="form-control datetimepicker-input{{ $errors->has('reservation-from') ? ' is-invalid' : '' }}" data-target="#reservation-from" value="{{ old('reservation-from', $reservation->getFromStr()) }}">
                                        <div class="input-group-append" data-target="#reservation-from" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="oi oi-clock"></i></div>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-from'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-from') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-to" class="col-md-4 col-form-label text-md-right">{{ __('messages.to') }}*</label>

                                <div class="col-md-6">
                                    <div class="input-group" id="reservation-to" data-target-input="nearest">
                                        <input type="text" name="reservation-to" class="form-control datetimepicker-input{{ $errors->has('reservation-to') ? ' is-invalid' : '' }}" data-target="#reservation-to" value="{{ old('reservation-to', $reservation->getToStr()) }}">
                                        <div class="input-group-append" data-target="#reservation-to" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="oi oi-clock"></i></div>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-to'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-to') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('messages.add-object') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection