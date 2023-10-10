@extends('layouts.admin-lte.main')
@section('breadcrumb')
@endsection
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Article Tags',
                        'route' => 'tag-article',
                    ])
                    <div class="card-body">
                        {!! $dataTable->table([
                            'class' => 'table table-bordered table-hover data-table dt-responsive display',
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
