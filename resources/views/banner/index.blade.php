@extends('layouts.admin-lte.main')
@section('content')
<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('partial.alert')
            <div class="card">
                @if ($banner->count() < 0)
                @include('partial.cardHeader', ['title'=> "Banner", "route" => "banner"])
                @endif
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover data-table dt-responsive display', 'width' => '100%', 'cellspacing' => '0']) !!}
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