@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" role="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                        <h4 class="align-middle card-title">
                            {{ $sharedObject->designation }}
                            <a href="{{ route('sharedObjects.edit',['id' => $sharedObject->id]) }}" class="btn btn-outline-primary">
                                <span class="oi oi-pencil"></span>
                            </a>
                        </h4>

                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            {{ $sharedObject->description }}
                        </p>
                        <table class="creation-info table">
                            <tbody>
                                <tr>
                                    <td><b>{{ __('messages.created') }}</b></td>
                                    <td>{{ __('messages.created-text', ['CREATED_AT'  => $sharedObject->createdAt(), 'CREATED_BY' => $sharedObject->createdBy->username]) }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ __('messages.updated') }}</b></td>
                                    <td>{{ __('messages.updated-text', ['UPDATED_AT'  => $sharedObject->updatedAt(), 'UPDATED_BY' => $sharedObject->updatedBy->username]) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="align-middle">
                                        <a href="{{ route('mySharedObjectExport', ['id' => $sharedObject->id]) }}">
                                            <span class="oi oi-data-transfer-download"> {{ __('messages.shared-export') }}</span>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="align-middle">
                                        <a href="{{ route('mySharedObjectExport', ['id' => $sharedObject->id]) }}">
                                            <span class="oi oi-data-transfer-download"> {{ __('messages.my-export') }}</span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="user-table table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.users') }}</th>
                                    <th colspan="2">
                                        @if($users->count() > $sharedObject->users->count())
                                            <form action="{{ route('sharedObjects.addUser',['id' => $sharedObject->id]) }}" method="post" class="form-inline">
                                                @csrf

                                                <div class="form-group">
                                                    <select class="form-control" name="new-user" id="new-user">
                                                        @foreach($users as $user)
                                                            @if(!$sharedObject->hasUser($user))
                                                                <option value="{{ $user->id }}">{{ $user->username }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <button class="btn btn-outline-primary" type="submit" id="add-user-btn">
                                                        <span class="oi oi-plus"></span>
                                                        {{ __('messages.add-user') }}
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sharedObject->users as $user)
                                    <tr>
                                        <td></td>
                                        <td class="align-middle">
                                            {{ $user->username }}
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('sharedObjects.removeUser',['id' => $sharedObject->id, 'userId' => $user->id]) }}" class="btn btn-outline-danger del-btn">
                                                <span class="oi oi-trash"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" role="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                       <div class="float-left align-middle">
                            {{ __('messages.reservation') }}
                        </div>
                        <div class="float-right">
                            <a href="{{ route('reservations.createFor',['' => $sharedObject->id]) }}" class="btn btn-outline-success">
                                {{ __('messages.make-reservation') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($sharedObject->getRelaventReservations()->count() > 0)
                            <div class="row justify-content-center">
                                @foreach($sharedObject->getRelaventReservations() as $res)
                                    @component('layouts.reservation', [ 'reservation' => $res])
                                    @endcomponent
                                @endforeach
                            </div>
                        @else
                            <p class="card-text">
                                {{ __('messages.no-reservation') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    <div>
@endsection