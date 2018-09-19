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
                            <form action="" class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Object" aria-label="Shared Object" aria-describedby="button-find">
                                <div class="input-group-append">
                                    <button class="btn btn-toolbar" type="button" id="button-find">{{ __('messages.find') }}</button>
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
                        {{ __('messages.reservation-template') }}
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
                                        <td>{{ $template->sharedObject->designation }}</td>
                                        <td>{{ $template->getStartDateStr() }}</td>
                                        <td>{{ $template->getEndDateStr() }}</td>
                                        <td>
                                            <a href="{{ route('templates.show',['id' => $template->id]) }}" class="btn btn-outline-success">
                                                <span class="oi oi-eye"></span>
                                            </a>
                                            <a href="{{ route('templates.edit',['id' => $template->id]) }}" class="btn btn-outline-success">
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
                        {{ __('messages.reservation') }}
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
                                        <td>{{ $reservation->sharedObject->designation }}</td>
                                        <td>{{ $reservation->getDateStr() }}</td>
                                        <td>{{ $reservation->getFromStr() }}</td>
                                        <td>{{ $reservation->getToStr() }}</td>
                                        <td>
                                            <a href="{{ route('reservations.show', ['id' => $reservation->id]) }}" class="btn btn-outline-success">
                                                <span class="oi oi-eye"></span>
                                            </a>
                                            <a href="{{ route('reservations.edit', ['id' => $reservation->id]) }}" class="btn btn-outline-success">
                                                <span class="oi oi-pencil"></span>
                                            </a>
                                            <form class="del-btn" action="{{ route('reservations.destroy', ['id' => $reservation->id]) }}" method="POST">
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