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
                            User Activity
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover data-table dt-responsive display', 'width' => '100%', 'cellspacing' => '0']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection	
@section('javascript')
{!! $dataTable->scripts() !!}
@endsection