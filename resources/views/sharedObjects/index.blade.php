@extends('layouts.app')

@section('content')
    <div class="container">
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