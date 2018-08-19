@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('message.new-reservation', ['SHARED_OBJECT' => $sharedObject->designation]) }}
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" aria-label="{{ __('messages.reservation') }}">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection