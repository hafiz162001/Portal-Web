@extends('layouts.admin-lte.main')
@section('style')
    <style>
        .location_id {
            display: none;
            /* -moz-box-sizing: border-box;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            -webkit-box-sizing: border-box;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            box-sizing: border-box; */
        }

        .access-user-cashier {
            /* display: none; */
        }

        .access-user {
            /* display: none; */
        }

        */
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12" style="margin-top: 50px;">
                <div class="card">
                    <div class="card-header with-border">{{ __('Update Users') }}</div>
                    @if (session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('access-users.update', $users->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="username"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Username') }}</label>
                                        <div class="col-md-9">
                                            <input id="username" type="username"
                                                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                                name="username" value="{{ $users->username }}" required>
                                            @if ($errors->has('username'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Change Password') }}</label>
                                        <div class="col-md-9">
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
                                            class="col-md-3 col-form-label text-md-left">{{ __('Confirm Change Password') }}</label>
                                        <div class="col-md-9">
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation">
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="form-group row">
                                        <label for="fullname"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Full Name') }}</label>
                                        <div class="col-md-9">
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
                                            class="col-md-3 col-form-label text-md-left">{{ __('E-Mail Address') }}</label>
                                        <div class="col-md-9">
                                            <input id="email" type="email"
                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                name="email" value="{{ $users->email }}" required>
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
                                                <option value="VIEWER">CHOOSE</option>
                                                <option value="VIEWER" @if ($users->role_code == 'VIEWER') selected @endif>
                                                    VIEWER</option>
                                                <option value="MODERATOR" @if ($users->role_code == 'MODERATOR') selected @endif>
                                                    MODERATOR </option>
                                                <option value="ADMIN" @if ($users->role_code == 'ADMIN') selected @endif>
                                                    ADMIN </option>
                                                <option value="SUPERUSER" @if ($users->role_code == 'SUPERUSER') selected @endif>
                                                    SUPER USER</option>
                                            </select> --}}
                                            {{-- <select class="select2 form-control" name="role_code" id="roleCode"
                                                onchange="getSelectValue()">
                                                @foreach ($userRoles as $userRole)
                                                    <option @if ($users->role_code == $userRole->code) selected @endif
                                                        value="{{ $userRole->code }}">{{ $userRole->code }}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                            <select class="form-control select2New" name="role_code" id="roleCode"
                                                onchange="getSelectRole()">
                                                @foreach ($userRoles as $userRole)
                                                    <option @if ($users->role_code == $userRole->code) selected @endif
                                                        value="{{ $userRole->code }}">{{ $userRole->code }}
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
                                                        <option value="{{ $userCategory['value'] }}"
                                                            @if ($users->user_apps_category == $userCategory['value']) selected @endif>
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
                                                            <option value="{{ $blocLocation->id }}"
                                                                @if ($users->bloc_id == $blocLocation->id) selected @endif>
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
                                                {{-- <select class="select2 form-control" name="location_id" >
                                                    @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}"
                                                            @if ($users->location_id == $location->id) selected @endif>
                                                            {{ $location->name . ' | ' . $location->type }}
                                                        </option>
                                                    @endforeach
                                                </select> --}}
                                                <select class="form-control select2New" name="location_id" id="locationId">
                                                    <option value="">-- Select Location --</option>
                                                    @if ($users->location_id && $users->role_code == 'CASHIER')
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->id }}"
                                                                @if ($users->location_id == $location->id) selected @endif>
                                                                {{ $location->name . ' | ' . $location->type }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="active"
                                            class="col-md-3 col-form-label text-md-left">{{ __('Active') }}</label>
                                        <div class="col-md-8">
                                            <select class="form-control select2New" name="active">
                                                <option value="1" @if ($users->active == 1) selected @endif>
                                                    YES
                                                </option>
                                                <option value="0" @if ($users->active == 0) selected @endif>
                                                    NO
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6">
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
        {{-- start --}}
        {{-- <div class="row justify-content-center access-user-cashier">
            <div class="col-md-12" style="margin-top: 50px;">
                <div class="card">
                    <div class="card-header with-border">{{ __('Access Menu Cashier') }}</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Menu</th>
                                <th>?Grant</th>
                            </tr>
                            @forelse($menuCashier as  $menu)
                                <tr>
                                    <th> {{ $menu->name }} </th>
                                    <th>
                                        <label for="check-all-menu-{{ $menu->id }} "
                                            class="  col-form-label text-md-left">
                                            <input id="check-all-menu-{{ $menu->id }}" type="checkbox"
                                                class="minimal-red all-user-menu-cashier "
                                                data-menu="{{ $menu->id }}"
                                                @if (array_key_exists($menu->id, json_decode($users->accessmenu->groupBy('menu_id'), true))) {{ 'checked' }} @endif />
                                            All</label>
                                    </th>
                                </tr>
                                @forelse($menu->submenu as $submenu)
                                    @php
                                        $submenuCashier = ['dashboard', 'product', 'product-category', 'ticket-order', 'eat-order', 'profile'];
                                    @endphp
                                    @if (in_array($submenu->code, $submenuCashier))
                                        <tr>
                                            <td> &nbsp; &nbsp;{{ $submenu->name }}</td>
                                            <td> <input type="checkbox"
                                                    class="minimal add-user-menu-cashier all-check-menu-cashier-{{ $menu->id }} "
                                                    data-user="{{ $users->id }}" data-menu="{{ $menu->id }}"
                                                    data-submenu="{{ $submenu->id }}"
                                                    @if (array_key_exists($submenu->id, json_decode($users->accessmenu->groupBy('sub_menu_id'), true))) {{ 'checked' }} @endif /> </td>
                                        </tr>
                                    @endif
                                @empty
                                @endforelse
                            @empty
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row justify-content-center access-user">
            <div class="col-md-12" style="margin-top: 50px;">
                <div class="card">
                    <div class="card-header with-border">{{ __('Access Menu') }}</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Menu</th>
                                <th>?Grant</th>
                            </tr>
                            @forelse($menus as  $menu)
                                <tr>
                                    <th> {{ $menu->name }} </th>
                                    <th>
                                        <label for="check-all-menu-{{ $menu->id }} "
                                            class="  col-form-label text-md-left">
                                            <input id="check-all-menu-{{ $menu->id }}" type="checkbox"
                                                class="minimal-red all-user-menu " data-menu="{{ $menu->id }}"
                                                @if (array_key_exists($menu->id, json_decode($users->accessmenu->groupBy('menu_id'), true))) {{ 'checked' }} @endif />
                                            All</label>
                                    </th>
                                </tr>
                                @forelse($menu->submenu as $submenu)
                                    <tr>
                                        <td> &nbsp; &nbsp; {{ $submenu->name }}</td>
                                        <td> <input type="checkbox"
                                                class="minimal add-user-menu all-check-menu-{{ $menu->id }} "
                                                data-user="{{ $users->id }}" data-menu="{{ $menu->id }}"
                                                data-submenu="{{ $submenu->id }}"
                                                @if (array_key_exists($submenu->id, json_decode($users->accessmenu->groupBy('sub_menu_id'), true))) {{ 'checked' }} @endif /> </td>
                                    </tr>
                                @empty
                                @endforelse
                            @empty
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- end --}}
    </div>
@endsection
@section('javascript')
    <script>
        // function getSelectValue(event) {
        //     var selectedValue = document.getElementById("roleCode").value;
        //     if (selectedValue == "CASHIER") {
        //         $('.location_id').css("display", "inline").slideDown();
        //         // $('.access-user-cashier').css("display", "inline").slideDown();
        //         // $('.access-user').css("display", "none").hide();

        //         // $('.all-user-menu').prop('checked', false).change();
        //         // $('.all-user-menu-cashier').prop('checked', false).change();
        //     } else {
        //         $('.location_id').css("display", "none").hide();
        //         // $('.access-user-cashier').css("display", "none").hide();
        //         // $('.access-user').css("display", "inline").slideDown();
        //     }
        // }
        // $(document).ready(function() {
        //     $('.select2New').select2({
        //         width: '100%'
        //     });


        //     var selectedValue = document.getElementById("roleCode").value;
        //     if (selectedValue == "CASHIER") {
        //         $('.location_id').css("display", "inline").slideDown();
        //         // $('.access-user-cashier').css("display", "inline").slideDown();
        //         // $('.access-user').css("display", "none").hide();
        //     } else {
        //         $('.location_id').css("display", "none").hide();
        //         // $('.access-user-cashier').css("display", "none").hide();
        //         // $('.access-user').css("display", "inline").slideDown();
        //     }
        //     // let base_url = "{{ env('APP_URL') }}";
        //     var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //     // $(document).on('ifChanged', '.all-user-menu', function(event) {
        //     //     console.log("adsada");
        //     //     var thisobj = $(this);
        //     //     if (event.target.checked) {
        //     //         $('.all-check-menu-' + thisobj.data("menu")).iCheck('check');
        //     //     } else {
        //     //         $('.all-check-menu-' + thisobj.data("menu")).iCheck('uncheck');
        //     //     }
        //     // });
        //     $('.all-user-menu').change(function(event) {
        //         // console.log(event)
        //         var thisobj = $(this);
        //         if (event.target.checked) {
        //             $('.all-check-menu-' + thisobj.data("menu")).iCheck('check');
        //         } else {
        //             $('.all-check-menu-' + thisobj.data("menu")).iCheck('uncheck');
        //         }
        //     });

        //     $(document).on('ifChanged', '.add-user-menu', function(event) {
        //         // console.log(event)
        //         var thisobj = $(this);
        //         if (event.target.checked) {
        //             $.ajax({
        //                 method: "POST",
        //                 // url: base_url + "/api/users/menu/add",
        //                 url: "/api/users/menu/add",
        //                 data: {
        //                     user_id: thisobj.data("user"),
        //                     menu_id: thisobj.data("menu"),
        //                     sub_menu_id: thisobj.data("submenu"),
        //                     _token: CSRF_TOKEN
        //                 },
        //                 dataType: 'JSON',
        //                 beforeSend: function() {

        //                 },
        //                 error: function(event, response) {
        //                     alert("Data fail: " + data);

        //                     return false;
        //                 },
        //                 success: function(event, response) {}
        //             });
        //         } else {
        //             $.ajax({
        //                 method: "POST",
        //                 // url: base_url + "/api/users/menu/remove",
        //                 url: "/api/users/menu/remove",
        //                 data: {
        //                     user_id: thisobj.data("user"),
        //                     menu_id: thisobj.data("menu"),
        //                     sub_menu_id: thisobj.data("submenu"),
        //                     _token: CSRF_TOKEN
        //                 },
        //                 dataType: 'JSON',
        //                 beforeSend: function() {

        //                 },
        //                 error: function(event, response) {
        //                     alert("Data fail: " + event);
        //                     return false;
        //                 },
        //                 success: function(event, response) {}
        //             });
        //         }
        //     });

        //     $('.add-user-menu').change(function(event) {
        //         // console.log(event)
        //         var thisobj = $(this);
        //         if (event.target.checked) {
        //             $.ajax({
        //                 method: "POST",
        //                 // url: base_url + "/api/users/menu/add",
        //                 url: "/api/users/menu/add",
        //                 data: {
        //                     user_id: thisobj.data("user"),
        //                     menu_id: thisobj.data("menu"),
        //                     sub_menu_id: thisobj.data("submenu"),
        //                     _token: CSRF_TOKEN
        //                 },
        //                 dataType: 'JSON',
        //                 beforeSend: function() {

        //                 },
        //                 error: function(event, response) {
        //                     alert("Data fail: " + data);

        //                     return false;
        //                 },
        //                 success: function(event, response) {}
        //             });
        //         } else {
        //             $.ajax({
        //                 method: "POST",
        //                 // url: base_url + "/api/users/menu/remove",
        //                 url: "/api/users/menu/remove",
        //                 data: {
        //                     user_id: thisobj.data("user"),
        //                     menu_id: thisobj.data("menu"),
        //                     sub_menu_id: thisobj.data("submenu"),
        //                     _token: CSRF_TOKEN
        //                 },
        //                 dataType: 'JSON',
        //                 beforeSend: function() {

        //                 },
        //                 error: function(event, response) {
        //                     alert("Data fail: " + event);
        //                     return false;
        //                 },
        //                 success: function(event, response) {}
        //             });
        //         }
        //     });

        //     // $('.all-user-menu-cashier').change(function(event) {
        //     //     var thisobj = $(this);
        //     //     if (event.target.checked) {
        //     //         console.log(thisobj.data("menu").length);
        //     //         $('.all-check-menu-cashier-' + thisobj.data("menu")).iCheck('check');
        //     //     } else {
        //     //         $('.all-check-menu-cashier-' + thisobj.data("menu")).iCheck('uncheck');
        //     //     }
        //     // });

        //     // $(document).on('ifChanged', '.add-user-menu-cashier', function(event) {
        //     //     // console.log(event)
        //     //     var thisobj = $(this);
        //     //     if (event.target.checked) {
        //     //         // if ((selectedValue == "CASHIER" && thisobj.data("submenu") == 5) || (selectedValue ==
        //     //         //         "CASHIER" && thisobj.data("submenu") == 16) || (selectedValue ==
        //     //         //         "CASHIER" && thisobj.data("submenu") == 17) || (selectedValue ==
        //     //         //         "CASHIER" && thisobj.data("submenu") == 25) || (selectedValue ==
        //     //         //         "CASHIER" && thisobj.data("submenu") == 34) || (selectedValue ==
        //     //         //         "CASHIER" && thisobj.data("submenu") == 39)) 
        //     //         var selectedValue = document.getElementById("roleCode").value;
        //     //         var submenu = [5, 16, 17, 25, 34, 39];
        //     //         if (selectedValue == "CASHIER" && (submenu.includes(thisobj.data("submenu")))) {
        //     //             $.ajax({
        //     //                 method: "POST",
        //     //                 // url: base_url + "/api/users/menu/add",
        //     //                 url: "/api/users/menu/add",
        //     //                 data: {
        //     //                     user_id: thisobj.data("user"),
        //     //                     menu_id: thisobj.data("menu"),
        //     //                     sub_menu_id: thisobj.data("submenu"),
        //     //                     _token: CSRF_TOKEN
        //     //                 },
        //     //                 dataType: 'JSON',
        //     //                 beforeSend: function() {

        //     //                 },
        //     //                 error: function(event, response) {
        //     //                     alert("Data fail: " + data);

        //     //                     return false;
        //     //                 },
        //     //                 success: function(event, response) {}
        //     //             });
        //     //         }
        //     //     } else {
        //     //         $.ajax({
        //     //             method: "POST",
        //     //             // url: base_url + "/api/users/menu/remove",
        //     //             url: "/api/users/menu/remove",
        //     //             data: {
        //     //                 user_id: thisobj.data("user"),
        //     //                 menu_id: thisobj.data("menu"),
        //     //                 sub_menu_id: thisobj.data("submenu"),
        //     //                 _token: CSRF_TOKEN
        //     //             },
        //     //             dataType: 'JSON',
        //     //             beforeSend: function() {

        //     //             },
        //     //             error: function(event, response) {
        //     //                 alert("Data fail: " + event);
        //     //                 return false;
        //     //             },
        //     //             success: function(event, response) {}
        //     //         });
        //     //     }
        //     // });
        //     // $('.add-user-menu-cashier').change(function(event) {
        //     //     // console.log(event)
        //     //     var thisobj = $(this);
        //     //     if (event.target.checked) {
        //     //         // var submenuCashier = ['dashboard', 'product', 'product-category', 'ticket-order',
        //     //         //     'eat-order', 'profile'
        //     //         // ];

        //     //         var selectedValue = document.getElementById("roleCode").value;
        //     //         // var bool = @json($submenus);
        //     //         // console.log(thisobj.data("submenu"));

        //     //         // bool = bool.filter(submenu => submenu.id == thisobj.data("submenu"));
        //     //         // console.log();

        //     //         // console.log(thisobj.data("submenu") == bool['id']);
        //     //         var submenu = [5, 16, 17, 25, 34, 39];
        //     //         if (selectedValue == "CASHIER" && (submenu.includes(thisobj.data("submenu")))) {
        //     //             $.ajax({
        //     //                 method: "POST",
        //     //                 // url: base_url + "/api/users/menu/add",
        //     //                 url: "/api/users/menu/add",
        //     //                 data: {
        //     //                     user_id: thisobj.data("user"),
        //     //                     menu_id: thisobj.data("menu"),
        //     //                     sub_menu_id: thisobj.data("submenu"),
        //     //                     _token: CSRF_TOKEN
        //     //                 },
        //     //                 dataType: 'JSON',
        //     //                 beforeSend: function() {

        //     //                 },
        //     //                 error: function(event, response) {
        //     //                     alert("Data fail: " + data);

        //     //                     return false;
        //     //                 },
        //     //                 success: function(event, response) {}
        //     //             });
        //     //         }
        //     //     } else {
        //     //         $.ajax({
        //     //             method: "POST",
        //     //             // url: base_url + "/api/users/menu/remove",
        //     //             url: "/api/users/menu/remove",
        //     //             data: {
        //     //                 user_id: thisobj.data("user"),
        //     //                 menu_id: thisobj.data("menu"),
        //     //                 sub_menu_id: thisobj.data("submenu"),
        //     //                 _token: CSRF_TOKEN
        //     //             },
        //     //             dataType: 'JSON',
        //     //             beforeSend: function() {

        //     //             },
        //     //             error: function(event, response) {
        //     //                 alert("Data fail: " + event);
        //     //                 return false;
        //     //             },
        //     //             success: function(event, response) {}
        //     //         });
        //     //     }
        //     // });

        //     // $('select[name=role_code]').change(function() {
        //     //     const $select = "CASHIER";
        //     //     if ($(this).find('option:contains(CASHIER) option:selected')) {
        //     //         $('.location_id').css("display", "inline").slideDown();
        //     //     }
        //     //     if ($(this).find('option:selected').attr("name") == $select) {
        //     //         $('.location_id').css("display", "inline").slideDown();
        //     //     }
        //     // });
        // });



        function getSelectCategory() {
            var selectedCategory = document.getElementById("userAppsCategory").value;
            var selectedRole = document.getElementById("roleCode").value;

            $('#locationId').val('');
            $('#blocId').val('');
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
                $('.all-user-menu').iCheck('uncheck').change();
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
        // test bloc location 
        function getSelectRole() {
            // $('#blocId').val('').change();
            // $('#userAppsCategory').val('').change();

            var selectedCategory = document.getElementById("userAppsCategory").value;
            var selectedRole = document.getElementById("roleCode").value;
            // if (selectedValue == "CASHIER") {
            //     $('.bloc_id').css("display", "inline").slideDown();
            //     // $('.location_id').css("display", "inline").slideDown();
            //     $('.all-user-menu').iCheck('uncheck').change();
            // }

            // if (selectedValue == "SUPERUSER") {
            //     $('.bloc_id').css("display", "none").hide();
            //     $('.location_id').css("display", "none").hide();
            // }
            // if (selectedValue != "SUPERUSER" && selectedValue != "CASHIER") {
            //     $('.bloc_id').css("display", "inline").slideDown();
            //     $('.location_id').css("display", "none").hide();
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

                if (selectedRole == "CASHIER") {
                    $('#blocId').val('').change();
                    $('#locationId').val('').change();

                    $('#userAppsCategory').val('');
                    $("#blocId").val($("#blocId option:first").val()).change();
                    $("#locationId").val($("#locationId option:first").val()).change();
                    $('.user_apps_category').css("display", "none").hide();
                    $('.bloc_id').css("display", "inline").slideDown();
                    $('.location_id').css("display", "inline").slideDown();
                    $('.all-user-menu').iCheck('uncheck').change();
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
                    $('.all-user-menu').iCheck('uncheck').change();
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
            var selectedCategory = document.getElementById("userAppsCategory").value;
            var selectedRole = document.getElementById("roleCode").value;
            $('.select2New').select2({
                width: '100%'
            });
            // $('#bloc_id').html(`<option>-- Select Bloc --</option>`);

            var selectedValue = document.getElementById("roleCode").value;

            // if (selectedValue == "CASHIER") {
            //     $('#bloc_id').change();
            //     $('.bloc_id').css("display", "inline").slideDown();
            //     $('.location_id').css("display", "inline").slideDown();
            // }

            // if (selectedValue == "SUPERUSER") {
            //     $('.bloc_id').css("display", "none").hide();
            //     $('.location_id').css("display", "none").hide();
            // }
            // if (selectedValue != "SUPERUSER" && selectedValue != "CASHIER") {
            //     $('.bloc_id').css("display", "inline").slideDown();
            //     $('.location_id').css("display", "none").hide();
            // }
            // if (selectedCategory == "evoria") {
            //     $('#locationId').val('');
            //     $('#blocId').val('');
            //     $('.bloc_id').css("display", "none").hide();
            //     $('.location_id').css("display", "none").hide();
            // }

            if (selectedRole != "") {
                // $('#locationId').val('');
                // $('#blocId').val('');
                // $('#userAppsCategory').val('');
                if (selectedRole == "CASHIER") {
                    $('#userAppsCategory').val('');
                    // $("#blocId").val($("#blocId option:first").val()).change();
                    // $("#locationId").val($("#locationId option:first").val()).change();
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
                    // $('#locationId').val('');
                    // $('#blocId').val('');
                    // $("#userAppsCategory").val($("#userAppsCategory option:first").val()).change();
                    // $("#blocId").val($("#blocId option:first").val()).change();

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

            $('#blocId').on('change', function() {
                const id = $(this).val();
                let html = `<option value="">-- Select Location --</option>`
                var selectedValue = document.getElementById("roleCode").value;
                if (selectedValue == "CASHIER") {
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
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.all-user-menu').change(function(event) {
                var thisobj = $(this);
                if (event.target.checked) {
                    $('.all-check-menu-' + thisobj.data("menu")).iCheck('check');
                } else {
                    $('.all-check-menu-' + thisobj.data("menu")).iCheck('uncheck');
                }
            });

            $(document).on('ifChanged', '.add-user-menu', function(event) {
                var thisobj = $(this);
                if (event.target.checked) {
                    $.ajax({
                        method: "POST",
                        // url: base_url + "/api/users/menu/add",
                        url: "/api/users/menu/add",
                        data: {
                            user_id: thisobj.data("user"),
                            menu_id: thisobj.data("menu"),
                            sub_menu_id: thisobj.data("submenu"),
                            _token: CSRF_TOKEN
                        },
                        dataType: 'JSON',
                        beforeSend: function() {

                        },
                        error: function(event, response) {
                            alert("Data fail: " + data);

                            return false;
                        },
                        success: function(event, response) {}
                    });
                } else {
                    $.ajax({
                        method: "POST",
                        // url: base_url + "/api/users/menu/remove",
                        url: "/api/users/menu/remove",
                        data: {
                            user_id: thisobj.data("user"),
                            menu_id: thisobj.data("menu"),
                            sub_menu_id: thisobj.data("submenu"),
                            _token: CSRF_TOKEN
                        },
                        dataType: 'JSON',
                        beforeSend: function() {

                        },
                        error: function(event, response) {
                            alert("Data fail: " + event);
                            return false;
                        },
                        success: function(event, response) {}
                    });
                }
            });

            $('.add-user-menu').change(function(event) {
                // console.log(event)
                var thisobj = $(this);
                if (event.target.checked) {
                    $.ajax({
                        method: "POST",
                        // url: base_url + "/api/users/menu/add",
                        url: "/api/users/menu/add",
                        data: {
                            user_id: thisobj.data("user"),
                            menu_id: thisobj.data("menu"),
                            sub_menu_id: thisobj.data("submenu"),
                            _token: CSRF_TOKEN
                        },
                        dataType: 'JSON',
                        beforeSend: function() {

                        },
                        error: function(event, response) {
                            alert("Data fail: " + data);

                            return false;
                        },
                        success: function(event, response) {}
                    });
                } else {
                    $.ajax({
                        method: "POST",
                        // url: base_url + "/api/users/menu/remove",
                        url: "/api/users/menu/remove",
                        data: {
                            user_id: thisobj.data("user"),
                            menu_id: thisobj.data("menu"),
                            sub_menu_id: thisobj.data("submenu"),
                            _token: CSRF_TOKEN
                        },
                        dataType: 'JSON',
                        beforeSend: function() {

                        },
                        error: function(event, response) {
                            alert("Data fail: " + event);
                            return false;
                        },
                        success: function(event, response) {}
                    });
                }
            });
        });
    </script>
@endsection
