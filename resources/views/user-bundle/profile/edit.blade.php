@extends('layouts.admin-lte.main')

@section('content')
    <div class="container">
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header with-border">
                            {{ __('Update Profile Users') }}
                            {{-- <h3 class="card-title">{{ __('Update Profile Users') }}</h3> --}}
                        </div>
                        @if (session('message'))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @endif
                        @include('partial.alert')
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile-web.update', $users->id) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="fullname"
                                        class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-7">
                                        <input id="fullname" type="text"
                                            class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}"
                                            name="fullname" value="{{ $users->fullname }}" required autofocus>

                                        @if ($errors->has('fullname'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('fullname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email"
                                        class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-7">
                                        <input id="email" type="email" readonly
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            name="email" value="{{ $users->email }}" required>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="role_code"
                                        class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
                                    <div class="col-md-7">
                                        <select class="select2 form-control" name="role_code" id="role_code" disabled>
                                            @foreach ($userRoles as $userRole)
                                                <option @if ($users->role_code == $userRole->code) selected @endif
                                                    value="{{ $userRole->code }}">{{ $userRole->code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password"
                                        class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-7">
                                        <input id="password" type="password"
                                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            name="password">

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password-confirm"
                                        class="col-md-3 col-form-label text-md-right">{{ __('Confirm Change Password') }}</label>
                                    <div class="col-md-7">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Update') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
