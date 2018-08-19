@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
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
        <div class="row justify-content-center mt-1">
            <div class="col-md-8">
                <div class="card">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col" class="align-middle">{{ __('messages.shared-object') }}</th>
                            <th scope="col" class="text-right">
                                <a href="{{ route('sharedObjects.create') }}" class="btn btn-outline-success glyphicon-new-window">{{ __('messages.new-object') }}</a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sharedObjects as $sharedObject)
                            <tr>
                                <td scope="row" class="col-md-6 align-middle">{{ $sharedObject->designation }}</td>
                                <td class="text-right col-md-2">
                                    <a href="{{ route('sharedObjects.show',['id' => $sharedObject->id]) }}" class="btn btn-outline-primary">
                                        <span class="oi oi-eye"></span>
                                    </a>
                                    <a href="{{ route('sharedObjects.edit',['id' => $sharedObject->id]) }}" class="btn btn-outline-primary">
                                        <span class="oi oi-pencil"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    <div>
@endsection