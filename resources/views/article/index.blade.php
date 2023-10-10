@extends('layouts.admin-lte.main')
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Article',
                        'route' => 'article',
                    ])
                    <div class="card-body table-responsive">
                        <div class="row">
                            {{-- <div class="col-md-3 mx-2">
                                <x-select :isStack="1" globalAttribute="category" label="Category" select2Class="select2New">
                                    <option selected value=""></option>
                                    @foreach ($category as $ctg)
                                        <option value="{{ $ctg['id'] }}">{{ $ctg['name'] }}</option>
                                    @endforeach
                                </x-select>
                            </div> --}}
                        </div>
                        {{-- <div class="form-group row mb-0">
                            <div class="col">
                                <button type="button" id="reset" class="btn btn-secondary btn-sm mb-3">
                                    <i class="fa fa-refresh"></i> {{ __('Reset') }}
                                </button>
                            </div>
                        </div> --}}
                        {!! $dataTable->table([
                            'class' => 'table table-bordered table-hover data-table dt-responsive display text-justify',
                            'width' => '100%',
                            'cellspacing' => '0',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partial.modalImage')
@endsection
@section('javascript')
    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function() {
            // call select2 for full width
            $('.select2New').select2({
                width: '100%',
                placeholder: '-- Choose --',
                allowClear: true
            });

            // filter table
            let table = $('#article-table')
            // $('#category').on('change', function() {
            //     tableFilterCategory($('#category').val())
            //     table.DataTable().ajax.reload()
            // })

            $('#category').on('change', function() {
                tableFilterType($('#category').val())
                table.DataTable().ajax.reload()
            })
            $('#reset').on('click', function() {
                tableFilterType(null)

                // $("#category").val($("#category option:first").val()).change()
                $("#category").val($("#category option:first").val()).change()
                table.DataTable().ajax.reload()
            })

            // function tableFilterCategory(value) {
            //     table.on('preXhr.dt', function(e, settings, data) {
            //         data.category = value;
            //     })
            // }

            function tableFilterType(value) {
                table.on('preXhr.dt', function(e, settings, data) {
                    data.category_id = value;
                })
            }
        });
    </script>
@endsection
{{-- @include('partial.modalDetail') --}}
