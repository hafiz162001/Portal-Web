@extends('layouts.admin-lte.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        @include('partial.alert')
        <div class="card">
            @include('partial.cardHeader', ['title'=> "Edit Navigation", "route" => "navi"])

            <div class="card-body">
                <form method="POST" action="{{ route('navi.update', $navi->id) }}">
                    @csrf
                    @method('put')
                    <x-input type="text" :defaultValue="$navi->code" :isRequired="true" globalAttribute="code" label="Code"></x-input>
                    
                    <x-input type="text" :defaultValue="$navi->name" :isRequired="true" globalAttribute="name" label="Name"></x-input>
                    
                    <x-input type="number" :defaultValue="$navi->order" globalAttribute="order" :isRequired="true" customAttribute="min=0" label="Order" ></x-input>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
