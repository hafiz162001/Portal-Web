@extends('layouts.admin-lte.main')
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    <div class="card-header with-border">
                        <div class="row w-100 justify-content-between">
                            <div class="col-auto align-self-center">
                                Term & Condition : <strong>{{ $termCondition->page_title }}</strong>
                            </div>
                            <div class="col-auto text-right mr-0 pr-0">
                                <a href="{{ route('term-condition' . '.index') }}" class="btn btn-primary">List Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover term-condition">
                            <thead>
                                <tr>
                                    {{-- <th>Id</th>
                                    <th>Name</th>
                                    <th>Title</th> --}}
                                    <th>Content</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- <td>{{ $termCondition->id ?? '-' }}</td>
                                    <td>{{ $termCondition->page_nm ?? '-' }}</td>
                                    <td>{{ $termCondition->page_title ?? '-' }}</td> --}}
                                    {{-- <td class="tinyMCE">{{ $termCondition->page_content ?? '-' }}</td> --}}
                                    <td>
                                        <textarea class="textarea tinyMCE form-control" rows="20" disabled>{{ $termCondition->page_content }}</textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @include('partial.modalImage')
@endsection
@push('scripts')
    <script src="https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        $('.term-condition').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            stateSave: true,
            "bDestroy": true,
            columnDefs: [{
                targets: '_all',
                className: 'dt-body-justify'
            }],
            "order": [
                [0, "desc"]
            ]
        });

        $(document).ready(function() {
            tinymce.init({
                selector: '.tinyMCE',
                branding: false,
                menubar: false,
                statusbar: false,
                readonly: 1,
                // autoresize: true,
                // theme_advanced_resizing: true,
                // theme_advanced_resizing_use_cookie: false,
                toolbar: false,
                mobile: {
                    branding: false,
                    menubar: false,
                    statusbar: false,
                    toolbar: false,
                },
                init_instance_callback: function(editor) {
                    var freeTiny = document.querySelector('.tox .tox-notification--in');
                    freeTiny.style.display = 'none';
                }
            });
        });
    </script>
@endpush
