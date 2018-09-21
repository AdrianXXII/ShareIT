@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-1">
                    <div class="card-header search-header" role="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                        <span class="oi oi-magnifying-glass"></span>
                        {{ __('messages.search') }}
                    </div>
                    <div class="collapse" id="collapseSearch">
                        <div class="card-body">
                            <form action="{{ route('reservations.search') }}" class="input-group mb-3">
                                <label for="fromDate" class="col-form-label">{{ __('messages.from') }}</label>
                                <div class="input-group date" id="fromDate" data-target-input="nearest">
                                    <input type="text" name="fromDate" class="form-control datetimepicker-input" data-target="#fromDate" value="{{ old('fromDate', $fromDate) }}">
                                    <div class="input-group-append" data-target="#fromDate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="oi oi-calendar"></i></div>
                                    </div>
                                </div>
                                <label for="toDate" class="col-form-label">{{ __('messages.to') }}</label>
                                <div class="input-group date" id="toDate" data-target-input="nearest">
                                    <input type="text" name="toDate" class="form-control datetimepicker-input" data-target="#toDate" value="{{ old('toDate', $toDate) }}">
                                    <div class="input-group-append" data-target="#toDate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="oi oi-calendar"></i></div>
                                    </div>
                                </div>
                                <label for="shared_object_id" class="col-form-label">{{ __('messages.shared-object') }}</label>

                                <div class="input-group mb-1">
                                    <select class="form-control" name="shared_object_id" id="shared_object_id">
                                        <option value=""></option>
                                        @foreach($sharedObjects as $object)
                                            <option value="{{ $object->id }}" {{ $object == old('sharedObject',$sharedObject)?'SELECTED':'' }}>{{ $object->designation }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group">
                                    <button class="btn btn-toolbar" type="submit" id="button-find">
                                        {{ __('messages.find') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8  mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('messages.reservation-template') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.shared-object') }}</th>
                                    <th>{{ __('messages.start-date') }}</th>
                                    <th>{{ __('messages.end-date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                    <tr>
                                        <td>
                                            {{ $template->sharedObject->designation }}
                                            @if(strlen($template->reason) >= 1)
                                                <br><span class="font-italic">{{ $template->reason }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $template->getStartDateStr() }}</td>
                                        <td>{{ $template->getEndDateStr() }}</td>
                                        <td>
                                            <a href="{{ route('templates.show',['id' => $template->id]) }}" class="btn btn-outline-primary">
                                                <span class="oi oi-eye"></span>
                                            </a>
                                            <a href="{{ route('templates.edit',['id' => $template->id]) }}" class="btn btn-outline-primary">
                                                <span class="oi oi-pencil"></span>
                                            </a>
                                            <form action="{{ route('templates.destroy',['id' => $template->id]) }}" method="POST" class="del-btn">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <span class="oi oi-trash"></span>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('messages.reservation') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.shared-object') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.from') }}</th>
                                    <th>{{ __('messages.to') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations  as $reservation)
                                    <tr>
                                        <td>
                                            {{ $reservation->sharedObject->designation }}
                                            @if(strlen($reservation->reason) >= 1)
                                                <br><span class="font-italic">{{ $reservation->reason }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $reservation->getDateStr() }}

                                            @if ($reservation->conflicts()->count() > 0)
                                                <div class="alert alert-warning text-center" role="alert">
                                                    {{ __('messages.in-conflict') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $reservation->getFromStr() }}</td>
                                        <td>{{ $reservation->getToStr() }}</td>
                                        <td>
                                            <a href="{{ route('reservations.show', ['id' => $reservation->id]) }}" class="btn btn-outline-primary">
                                                <span class="oi oi-eye"></span>
                                            </a>
                                            <a href="{{ route('reservations.edit', ['id' => $reservation->id]) }}" class="btn btn-outline-primary">
                                                <span class="oi oi-pencil"></span>
                                            </a>
                                            <form class="del-btn" action="{{ route('reservations.destroy', ['id' => $reservation->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <span class="oi oi-trash"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection