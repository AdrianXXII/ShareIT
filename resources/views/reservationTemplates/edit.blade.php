@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="align-middle card-title">
                            {{ __('messages.reservation-for', ['SHARED_OBJECT' => $template->sharedObject->designation]) }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('templates.update', ['id' => $template->id]) }}" method="POST" aria-label="{{ __('messages.reservation') }}" class="edit-recurring">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="reason" class="col-md-4 col-form-label text-md-right">{{ __('messages.reason') }}</label>


                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('reason') ? ' is-invalid' : '' }}" type="text" name="reason" id="reason" value="{{ old('reason', $template->reason) }}">

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
                                        <option {{ (old('priority', $template->priority) == \App\Reservation::PRIORITY_FLEXIBLE )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_FLEXIBLE }}">{{ __('messages.flexible') }}</option>
                                        <option {{ (old('priority', $template->priority) == \App\Reservation::PRIORITY_HIGH )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_HIGH }}">{{ __('messages.high') }}</option>
                                        <option {{ (old('priority', $template->priority) == \App\Reservation::PRIORITY_MIDDLE )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_MIDDLE }}">{{ __('messages.middle') }}</option>
                                        <option {{ (old('priority', $template->priority) == \App\Reservation::PRIORITY_LOW )?'SELECTED':'' }} value="{{ \App\Reservation::PRIORITY_LOW }}">{{ __('messages.low') }}</option>
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
                                        {{ __('messages.repeating') }}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-date" class="col-md-4 col-form-label text-md-right">{{ __('messages.date') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group date{{ $errors->has('reservation-date') ? ' is-invalid' : '' }}" id="reservation-date" data-target-input="nearest">
                                        <input type="text" name="reservation-date" class="form-control datetimepicker-input" data-target="#reservation-date" value="{{ old('reservation-date', $template->getFirstDate()) }}">
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
                                        <input type="text" name="reservation-from" class="form-control datetimepicker-input{{ $errors->has('reservation-from') ? ' is-invalid' : '' }}" data-target="#reservation-from" value="{{ old('reservation-from', $template->getFromStr()) }}">
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
                                        <input type="text" name="reservation-to" class="form-control datetimepicker-input{{ $errors->has('reservation-to') ? ' is-invalid' : '' }}" data-target="#reservation-to" value="{{ old('reservation-to', $template->getToStr()) }}">
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

                            <div class="form-group row">
                                <label for="reservation-is-date-based" class="col-md-4 col-form-label text-md-right">{{ __('messages.is-date-based') }}*</label>

                                <div class="col-md-6">
                                    <div class="input-group" id="reservation-is-date-based">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" {{ (old('reservation-is-date-based', $template->is_day_based?'true':'false') == 'true')?'CHECKED':'' }} name="reservation-is-date-based" type="radio" id="reservation-is-date-based-yes" value="true">
                                            <label class="form-check-label" for="reservation-is-date-based-yes">{{ __('messages.yes') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" {{ (old('reservation-is-date-based', $template->is_day_based?'true':'false') == 'false')?'CHECKED':'' }} name="reservation-is-date-based" type="radio" id="reservation-is-date-based-no" value="false">
                                            <label class="form-check-label" for="reservation-is-date-based-no">{{ __('messages.no') }}</label>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-frequency'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-frequency') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-frequency" class="col-md-4 col-form-label text-md-right">{{ __('messages.frequency') }}*</label>

                                <div class="col-md-6">
                                    <div class="input-group" id="reservation-frequency">
                                        <div class="form-check form-check-inline notDateBase">
                                            <input class="form-check-input" {{ (old('reservation-frequency', $template->weekly_frequency?'weekly':'') == 'weekly')?'CHECKED':'' }} name="reservation-frequency" type="radio" id="reservation-weekly" value="weekly">
                                            <label class="form-check-label" for="reservation-weekly">{{ __('messages.weekly') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" {{ (old('reservation-frequency', $template->monthly_frequency?'monthly':'') == 'monthly')?'CHECKED':'' }} name="reservation-frequency" type="radio" id="reservation-monthly" value="monthly">
                                            <label class="form-check-label" for="reservation-monthly">{{ __('messages.monthly') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" {{ (old('reservation-frequency', $template->yearly_frequency?'yearly':'') == 'yearly')?'CHECKED':'' }} name="reservation-frequency" type="radio" id="reservation-yearly" value="yearly">
                                            <label class="form-check-label" for="reservation-yearly">{{ __('messages.yearly') }}</label>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-frequency'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-frequency') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row notDateBase">
                                <label for="reservation-days" class="col-md-4 col-form-label text-md-right">{{ __('messages.days') }}*</label>

                                <div class="col-md-6">
                                    <div class="input-group" id="reservation-days">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-monday" {{ (old('reservation-days', $template->monday?'monday':'') == 'monday')?'CHECKED':'' }} value="monday">
                                            <label class="form-check-label" for="reservation-monday">{{ __('messages.monday') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-tueday" {{ (old('reservation-days', $template->tuesday?'tueday':'') == 'tueday')?'CHECKED':'' }} value="tueday">
                                            <label class="form-check-label" for="reservation-tueday">{{ __('messages.tueday') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-wednesday" {{ (old('reservation-days', $template->wednesday?'wednesday':'') == 'wednesday')?'CHECKED':'' }} value="wednesday">
                                            <label class="form-check-label" for="reservation-wednesday">{{ __('messages.wednesday') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-thursday" {{ (old('reservation-days', $template->thursday?'thursday':'') == 'thursday')?'CHECKED':'' }} value="thursday">
                                            <label class="form-check-label" for="reservation-thursday">{{ __('messages.thursday') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-friday" {{ (old('reservation-days', $template->friday?'friday':'') == 'friday')?'CHECKED':'' }} value="friday">
                                            <label class="form-check-label" for="reservation-friday">{{ __('messages.friday') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-saturday" {{ (old('reservation-days', $template->saturday?'saturday':'') == 'saturday')?'CHECKED':'' }} value="saturday">
                                            <label class="form-check-label" for="reservation-saturday">{{ __('messages.saturday') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="reservation-days[]" type="checkbox" id="reservation-sunday" {{ (old('reservation-days', $template->sunday?'sunday':'') == 'sunday')?'CHECKED':'' }} value="sunday">
                                            <label class="form-check-label" for="reservation-sunday">{{ __('messages.sunday') }}</label>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-days'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-days') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-start-date" class="col-md-4 col-form-label text-md-right">{{ __('messages.start-date') }}*</label>

                                <div class="col-md-6">
                                    <div class="input-group date" id="reservation-start-date" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input{{ $errors->has('reservation-start-date') ? ' is-invalid' : '' }}" data-target="#reservation-start-date" name="reservation-start-date" value="{{ old('reservation-start-date', $template->getStartDateStr()) }}">
                                        <div class="input-group-append" data-target="#reservation-start-date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="oi oi-calendar"></i></div>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-start-date'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-start-date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-end-date" class="col-md-4 col-form-label text-md-right">{{ __('messages.end-date') }}*</label>

                                <div class="col-md-6">
                                    <div class="input-group date" id="reservation-end-date" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input{{ $errors->has('reservation-end-date') ? ' is-invalid' : '' }}" data-target="#reservation-end-date" name="reservation-end-date" value="{{ old('reservation-end-date', $template->getEndDateStr()) }}">
                                        <div class="input-group-append" data-target="#reservation-end-date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="oi oi-calendar"></i></div>
                                        </div>
                                    </div>

                                    @if ($errors->has('reservation-end-date'))
                                        <span class="invalid-feedback invalid-group-feedback" role="alert">
                                            <strong>{{ $errors->first('reservation-end-date') }}</strong>
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