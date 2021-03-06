@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <h4 class="card-title">
                                {{ __('messages.my-upcoming') }}
                            </h4>
                        </div>
                        <div class="col-md-2 align-middle text-right">
                            <a href="{{ route('myexport') }}">
                                <span class="oi oi-data-transfer-download"></span> Export
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(Auth::user()->getRelaventReservations()->count() > 0)
                    <div class="row justify-content-center">
                        @foreach(Auth::user()->getRelaventReservations() as $res)
                            @component('layouts.reservation', [ 'reservation' => $res])
                                <p class="font-weight-bold">{{ $res->sharedObject->designation }}</p>
                            @endcomponent
                        @endforeach
                    </div>
                    @else
                        {{__('messages.no-reservation')  }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
