<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-tap-highlight" content="no">
    @yield('meta')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
        integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/fontawesome-free/css/v4-shims.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/dist/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iCheck/all.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/rowReorder.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/select2/css/select2.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/buttons.bootstrap4.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">


    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .app-sidebar__heading {
            font-weight: 400;
            font-size: 9px;
            line-height: 120%;
            letter-spacing: 0.02em;
            color: #FFFFFF;
            padding: 0.5rem 1rem;
        }

        .header-primary {
            background-color: blue;
            color: white;
        }

        .select2-container .select2-selection--single {
            height: auto;
            border: 1px solid #ced4da;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border: 1px solid #ced4da;
            max-height: calc(2.25rem + 2px) !important;
        }

        .select2-container--default .select2-container--below .select2-container--focus {
            border: 1px solid #ced4da;
            max-height: calc(2.25rem + 2px) !important;
        }

        .select2-container--default .select2-selection--single {
            max-height: calc(2.25rem + 2px) !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff;
            border-color: #006fe6;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
    @yield('style')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.admin-lte.navbar')
        <!-- /.navbar -->

        {{-- Sidebar --}}
        @include('layouts.admin-lte.sidebar')
        {{-- Sidebar --}}

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">@yield('title')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                @yield('content')
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        @include('layouts.admin-lte.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="{{ asset('admin-lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('css/iCheck/icheck.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-lte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset('admin-lte/plugins/datatables/jquery.dataTables.js') }}"></script> --}}
    <script src="{{ asset('admin-lte/plugins/datatables/dataTables.rowReorder.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/dataTables.responsive.min.js') }}"></script>

    {{-- <script src="{{ asset('admin-lte/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/vfs_fonts.js') }}"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="{{ asset('architect/js/scripts-init/sweet-alerts.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/select2/js/select2.full.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-2').select2();
        });
        $('.btn-danger').on("click", function(e) {
            e.preventDefault();
            var form = this.closest('form');
            Swal.fire({
                // title: 'Anda akan menghapus data ini!',
                title: 'Are you sure?',
                text: 'You want to delete this data!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((value) => {
                if (value.value) {
                    form.submit()
                }
            });
        });

        function deleteData(e) {
            e.preventDefault();
            Swal.fire({
                // title: 'Anda akan menghapus data ini?',
                title: 'Are you sure?',
                text: 'You want to delete this data!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((value) => {
                if (value.value) {
                    e.target.submit()
                }
            });
        }

        function logout(e) {
            var form = $('#logout-form');
            Swal.fire({
                // title: 'Yakin?',
                // text: 'Anda akan keluar dari aplikasi ini!',
                title: 'Are you sure?',
                text: 'Are you going to quit this app!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((value) => {
                console.log(value)
                if (value.value) {
                    form.submit()
                }
            });
        }

        function openModal(url) {
            console.log(url)
            var modalImg = $('#img-exampleModal');
            modalImg.attr('src', url);
            $('#exampleModal').modal('toggle')
        }

        // function openModalData(data) {
        //     console.log(data)
        //     var modalDetail = $('#detail-exampleModal');
        //     modalDetail.attr(data);
        // }
    </script>
    @yield('javascript')
    @stack('scripts')
    @stack('modal')
</body>

</html>
