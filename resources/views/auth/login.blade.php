@extends('layouts.architect.auth')
@section('content')
    <div class="h-100 bg-plum-plate bg-animation">
        <div class="d-flex h-100 justify-content-center align-items-center">
            <div class="mx-auto app-login-box col-md-8">
                <div class="app-logo-inverse mx-auto mb-3"></div>
                <div class="modal-dialog w-100 mx-auto">
                    <div class="modal-content">
                        <div class="modal-body">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}</div>
                                <div class="alert alert-success alert-dismissible fade show">{{ session('message') }}</div>
                            @endif
                            {{-- @if (session('message'))
                                <div class="alert alert-success">{{ session('message') }}</div>
                            @endif --}}
                            <div class="h5 modal-title text-center">
                                <h4 class="mt-2">
                                    <div>Welcome back,</div>
                                    <span>Please sign in to your account below.</span>
                                </h4>
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="position-relative form-check">
                                    <input name="remember" id="remember" type="checkbox" class="form-check-input"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember" class="form-check-label">Keep me logged in</label>
                                </div> --}}
                                <div class="modal-footer clearfix">
                                    <div class="float-right">
                                        <button class="btn btn-primary btn-lg">Login to Dashboard</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="text-center text-white opacity-8 mt-3">Copyright © MBloc {{ date('Y') }}</div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            window.setTimeout(function() {
                $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@endsection
