@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/rowReorder.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('admin-lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('admin-lte/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin-lte/plugins/datatables/buttons.print.min.js') }}"></script>
    <script>
    $(document).ready( function () {
        $('.table').DataTable({
            responsive:true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true },
                { extend: 'print', footer: true }
            ],
        });
        
    });
    </script>
@endsection