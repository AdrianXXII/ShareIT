@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('messages.edit-shared-object') }}
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('sharedObjects.update', ['id' => $sharedObject->id]) }}" aria-label="{{ __('messages.shared-object') }}">
                            @csrf

                            @method('PUT')
                            <div class="form-group row">
                                <label for="designation" class="col-md-4 col-form-label text-md-right">{{ __('messages.designation') }}</label>

                                <div class="col-md-6">
                                    <input id="designation" type="text" class="form-control{{ $errors->has('designation') ? ' is-invalid' : '' }}" name="designation" value="{{ old('designation', $sharedObject->designation) }}" required autofocus>

                                    @if ($errors->has('designation'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('designation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('messages.description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required >{{ old('description', $sharedObject->description) }}</textarea>

                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

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
                                </tbody>
                            </table>

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
    <div>
@endsection