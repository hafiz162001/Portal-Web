@extends('layouts.admin-lte.main')
@section('content')
<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                @include('partial.cardHeader', ['title'=> "Users", "route" => "access-users"])
                @if (session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
                @endif   
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
<script>
    function validate(){
        var agree=confirm("Are you sure?");
        if (agree)
            return true ;
        else
            return false ;
    }
</script>
@endsection