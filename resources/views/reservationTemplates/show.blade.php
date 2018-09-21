@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title align-middle">
                            {{ __('messages.reservation-template') }}
                            <a href="{{ route('templates.edit',['id' => $template->id]) }}" class="btn btn-outline-primary">
                                <span class="oi oi-pencil"></span>
                            </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>{{ __('messages.shared-object') }}</td>
                                <td>{{ $template->sharedObject->designation }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('messages.user') }}</td>
                                <td>{{ $template->user->username }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('messages.period') }}</td>
                                <td>{{ __('messages.from-to', ['FROM'=>$template->getFromStr(),'TO'=>$template->getToStr()]) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('messages.time-period') }}</td>
                                <td>{{ __('messages.starting-ending', ['START'=>$template->getStartDateStr(),'END'=>$template->getEndDateStr()]) }}</td>
                            </tr>
                            @if(strlen($template->reason) > 0)
                                <tr>
                                    <td>{{ __('messages.reason') }}</td>
                                    <td>{{ $template->reason }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{ __('messages.priority') }}</td>
                                <td>
                                    {{ $template->getPriority() }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @if($template->reservations->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                {{ _('messages.reservations') }}
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    @foreach($template->reservations as $res)
                                        @component('layouts.reservation', [ 'reservation' => $res])
                                        @endcomponent
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection