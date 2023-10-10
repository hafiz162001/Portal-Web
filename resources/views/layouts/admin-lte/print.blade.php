<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AdminLTE 3 | Starter</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-lte/dist/css/adminlte.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .header-primary{
      background-color: blue;
      color: white;
    }
    .page-break { display: none; }
    .border-jadwal{
        border: 3px solid black;
    }
    .border-jadwal-bottom{
        border-bottom: 3px solid black;
    }
    a:-webkit-any-link {
        text-decoration: none;
    }
    .DivIdToPrint {
        display: none;
        visibility: hidden;
    }
    @media print {
        @page { margin-top: 0mm; margin-bottom: 0mm; }
        body * {
            visibility: hidden;
        }
        .DivIdToPrint, .DivIdToPrint * {
            visibility: visible;
        }
        .DivIdToPrint {
            display: block;
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
        }
        .DivIdToPrint, .pagebreak * { 
            /* visibility: visible; */
             page-break-after:always; 
            display: block;
            float: none !important; 
            position: static !important;
        }
    }
  </style>
  @yield('style')
</head>
<body class="hold-transition sidebar-mini" onload="window.print()" onafterprint="window.close()">
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
    <div class="content DivIdToPrint py-5">
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
<script src="{{ asset('admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin-lte/dist/js/adminlte.min.js') }}"></script>
  <script>
    $(document).ready(function() {
    });
  </script>
@yield('script')
</body>
</html>
