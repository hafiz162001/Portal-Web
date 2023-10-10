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
@endsection
{{-- @include('partial.modalDetail') --}}
