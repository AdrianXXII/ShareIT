@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        @if(isset($sharedObject))
                            {{ __('messages.new-reservation-for', ['SHARED_OBJECT' => $sharedObject->designation]) }}
                        @else
                            {{ __('messages.new-reservation') }}
                        @end
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" aria-label="{{ __('messages.reservation') }}">
                            @csrf
                            @if(isset($sharedObject))
                                <input type="hidden" name="shared_object_id">
                            @else
                                <select name="shared_object_id" id="shared_object_id">
                                    @foreach($sharedObject as $object)
                                        <option value="{{ $object->id }}">{{ $object->designation }}</option>
                                </select>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection