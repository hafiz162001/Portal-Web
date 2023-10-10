@extends('layouts.admin-lte.main')
@section('style')
    <style>
        .location_id {
            display: none;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-10" style="margin-top: 50px;">
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Create User Access',
                        'route' => 'access-users',
                    ])
                    @if ($errors->count() > 0)
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('access-users.store') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="username"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Username') }}</label>
                                        <div class="col-md-9">
                                            <input id="username" type="username"
                                                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                                name="username" value="{{ old('username') }}" required>
                                            @if ($errors->has('username'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Password') }}</label>
                                        <div class="col-md-9">
                                            <input id="password" type="password"
                                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                name="password" required>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password-confirm"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Confirm Password') }}</label>
                                        <div class="col-md-9">
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" required>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="fullname"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Full Name') }}</label>
                                        <div class="col-md-9">
                                            <input id="fullname" type="text"
                                                class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}"
                                                name="fullname" value="{{ old('fullname') }}" required autofocus>
                                            @if ($errors->has('fullname'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('fullname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email"
                                            class="col-md-3 col-form-label text-md-left">{{ __('E-Mail Address') }}</label>
                                        <div class="col-md-9">
                                            <input id="email" type="email"
                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                name="email" value="{{ old('email') }}" required>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="role_code"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Role') }}</label>
                                        <div class="col-md-8">
                                            {{-- <select class="select2 form-control" name="role_code">
                                            <option value="VIEWER" >CHOOSE</option>
                                            <option value="VIEWER" >VIEWER</option>
                                            <option value="MODERATOR" >MODERATOR</option>
                                            <option value="ADMIN" >ADMIN</option>
                                            <option value="SUPERUSER" >SUPER USER</option>
                                        </select> --}}
                                            <select class="form-control select2New" name="role_code" id="roleCode"
                                                onchange="getSelectRole()" required>
                                                {{-- <select class="form-control select2New" name="role_code" id="roleCode"
                                                onchange="getSelectValue()"> --}}
                                                <option value="">-- Select Role --</option>
                                                @foreach ($userRoles as $userRole)
                                                    <option value="{{ $userRole->code }}">{{ $userRole->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="user_apps_category">
                                        <div class="form-group row ">
                                            <label for="user_apps_category"
                                                class="col-md-3 col-form-label text-md-left">{{ __('Category') }}</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2New" name="user_apps_category"
                                                    id="userAppsCategory" onchange="getSelectCategory()">
                                                    <option value="">-- Select Category --</option>
                                                    @foreach ($userCategories as $userCategory)
                                                        <option value="{{ $userCategory['value'] }}">
                                                            {{ $userCategory['text'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if (empty(Auth::user()->bloc_id))
                                        <div class="bloc_id">
                                            <div class="form-group row ">
                                                <label for="bloc_id"
                                                    class="col-md-3 col-form-label text-md-left">{{ __('Bloc') }}</label>
                                                <div class="col-md-8">
                                                    <select class="form-control select2New" name="bloc_id" id="blocId">
                                                        <option value="">-- Select Bloc --</option>
                                                        @foreach ($blocLocations as $blocLocation)
                                                            <option value="{{ $blocLocation->id }}">
                                                                {{ $blocLocation->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="location_id">
                                        <div class="form-group row ">
                                            <label for="location_id"
                                                class="col-md-3 col-form-label text-md-left">{{ __('Location') }}</label>
                                            <div class="col-md-8">
                                                {{-- <select class="select2 form-control" name="location_id" rel="location_id">
                                                    @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}">
                                                            {{ $location->name . ' | ' . $location->type }}
                                                        </option>
                                                    @endforeach
                                                </select> --}}
                                                <select class="form-control select2New" name="location_id" id="locationId"
                                                    disabled>
                                                    {{-- <option value="">Choice Location</option> --}}
                                                    {{-- @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}">
                                                            {{ $location->name . ' | ' . $location->type }}
                                                        </option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="active"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Active') }}</label>
                                        <div class="col-md-8">
                                            <select class="form-control select2New" name="active">
                                                <option value="1">YES</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function getSelectCategory() {
            var selectedCategory = document.getElementById("userAppsCategory").value;
            var selectedRole = document.getElementById("roleCode").value;

            $('#locationId').val('');
            $('#blocId').val('');
            // selectedLocation.value('');
            // selectedBloc.value('');
            // $("#userAppsCategory").val($("#userAppsCategory option:first").val()).change();
            // $("#locationId").val($("#locationId option:first").val()).change();
            // $("#blocId").val($("#blocId option:first").val()).change();
            if (selectedCategory == "") {
                $('#locationId').val('');
                $('#blocId').val('');
                // $('.user_apps_category').css("display", "none").hide();
                $('.bloc_id').css("display", "none").hide();
                $('.location_id').css("display", "none").hide();
            }
            if (selectedCategory == "blocx") {
                if (selectedRole == "CASHIER") {
                    $('#userAppsCategory').val('');
                    $('.user_apps_category').css("display", "none").hide();
                    $('.bloc_id').css("display", "inline").slideDown();
                    $('.location_id').css("display", "inline").slideDown();
                }

                if (selectedRole == "SUPERUSER") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $('.bloc_id').css("display", "none").hide();
                    $('.location_id').css("display", "none").hide();
                }
                if (selectedRole != "SUPERUSER" && selectedRole != "CASHIER") {
                    $('#locationId').val('');
                    $('.bloc_id').css("display", "inline").slideDown();
                    $('.location_id').css("display", "none").hide();
                }
            }
            if (selectedCategory == "evoria") {
                $('#locationId').val('');
                $('#blocId').val('');
                $('.bloc_id').css("display", "none").hide();
                $('.location_id').css("display", "none").hide();
            }

            $('#blocId').on('change', function() {
                const id = $(this).val();
                let html = `<option value="">-- Select Location --</option>`
                var selectedRole = document.getElementById("roleCode").value;
                if (selectedRole == "CASHIER") {
                    $('.location_id').css("display", "inline").slideDown();
                } else {
                    $('.location_id').css("display", "none").hide();
                }

                $.ajax({
                    type: "get",
                    url: "{{ route('getLocationAccessUser') }}",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {

                        $.each(response.location, (key, val) => {
                            html +=
                                `<option value="${val.id}">${val.name} | ${val.type}</option>`
                        })

                        $('#locationId').html(html)
                        $('#locationId').prop('disabled', false)

                    }
                });

            })
        }

        function getSelectRole() {
            var selectedRole = document.getElementById("roleCode").value;
            // if (selectedRole !== "") {
            //     if (selectedRole != "SUPERUSER" && selectedRole != "CASHIER") {
            //         $('.bloc_id').css("display", "none").hide();
            //         $('.user_apps_category').css("display", "inline").slideDown();
            //         $('.location_id').css("display", "none").hide();
            //     }
            // }

            if (selectedRole == "") {
                $('#locationId').val('');
                $('#blocId').val('');
                $('#userAppsCategory').val('');
                $('.bloc_id').css("display", "none").hide();
                $('.user_apps_category').css("display", "none").hide();
                $('.location_id').css("display", "none").hide();
            }

            if (selectedRole != "") {
                $('#locationId').val('');
                $('#blocId').val('');
                $('#userAppsCategory').val('');
                var selectedCategory = document.getElementById("userAppsCategory").value;
                if (selectedRole == "CASHIER") {
                    $('#userAppsCategory').val('');
                    $("#blocId").val($("#blocId option:first").val()).change();
                    $("#locationId").val($("#locationId option:first").val()).change();
                    $('.user_apps_category').css("display", "none").hide();
                    $('.bloc_id').css("display", "inline").slideDown();
                    $('.location_id').css("display", "inline").slideDown();
                }

                if (selectedRole == "SUPERUSER") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $('#userAppsCategory').val('');
                    $('.user_apps_category').css("display", "none").hide();
                    $('.bloc_id').css("display", "none").hide();
                    $('.location_id').css("display", "none").hide();
                }
                if (selectedRole != "SUPERUSER" && selectedRole != "CASHIER") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $("#userAppsCategory").val($("#userAppsCategory option:first").val()).change();
                    $("#blocId").val($("#blocId option:first").val()).change();

                    $('.bloc_id').css("display", "none").hide();
                    $('.user_apps_category').css("display", "inline").slideDown();
                    $('.location_id').css("display", "none").hide();
                }

                if (selectedCategory == "evoria") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $('.bloc_id').css("display", "none").hide();
                    $('.location_id').css("display", "none").hide();
                }
            }

            $('#blocId').on('change', function() {
                const id = $(this).val();
                let html = `<option value="">-- Select Location --</option>`
                var selectedRole = document.getElementById("roleCode").value;
                if (selectedRole == "CASHIER") {
                    $('.location_id').css("display", "inline").slideDown();
                } else {
                    $('.location_id').css("display", "none").hide();
                }

                $.ajax({
                    type: "get",
                    url: "{{ route('getLocationAccessUser') }}",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {

                        $.each(response.location, (key, val) => {
                            html +=
                                `<option value="${val.id}">${val.name} | ${val.type}</option>`
                        })

                        $('#locationId').html(html)
                        $('#locationId').prop('disabled', false)

                    }
                });

            })
        }
        $(document).ready(function() {
            $('.select2New').select2({
                width: '100%'
            });
            $('.bloc_id').css("display", "none").hide();
            $('.user_apps_category').css("display", "none").hide();
            $('.location_id').css("display", "none").hide();
            var selectedCategory = document.getElementById("userAppsCategory").value;
            var selectedRole = document.getElementById("roleCode").value;

            if (selectedRole == "") {
                $('#locationId').val('');
                $('#blocId').val('');
                $('#userAppsCategory').val('');
                $('.bloc_id').css("display", "none").hide();
                $('.user_apps_category').css("display", "none").hide();
                $('.location_id').css("display", "none").hide();
            }

            if (selectedRole != "") {
                $('#locationId').val('');
                $('#blocId').val('');
                $('#userAppsCategory').val('');
                if (selectedRole == "CASHIER") {
                    $('#userAppsCategory').val('');
                    $("#blocId").val($("#blocId option:first").val()).change();
                    $("#locationId").val($("#locationId option:first").val()).change();
                    $('.user_apps_category').css("display", "none").hide();
                    $('.bloc_id').css("display", "inline").slideDown();
                    $('.location_id').css("display", "inline").slideDown();
                }

                if (selectedRole == "SUPERUSER") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $('#userAppsCategory').val('');
                    $('.user_apps_category').css("display", "none").hide();
                    $('.bloc_id').css("display", "none").hide();
                    $('.location_id').css("display", "none").hide();
                }
                if (selectedRole != "SUPERUSER" && selectedRole != "CASHIER") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $("#userAppsCategory").val($("#userAppsCategory option:first").val()).change();
                    $("#blocId").val($("#blocId option:first").val()).change();

                    $('.bloc_id').css("display", "inline").slideDown();
                    $('.user_apps_category').css("display", "inline").slideDown();
                    $('.location_id').css("display", "none").hide();
                }

                if (selectedCategory == "evoria") {
                    $('#locationId').val('');
                    $('#blocId').val('');
                    $('.bloc_id').css("display", "none").hide();
                    $('.location_id').css("display", "none").hide();
                }
            }
        });
    </script>
@endpush
