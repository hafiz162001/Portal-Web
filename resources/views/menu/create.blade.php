@extends('layouts.admin-lte.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        @include('partial.alert')
        <div class="card">
            @include('partial.cardHeader', ['title'=> "Create Menu", "route" => "menu"])

            <div class="card-body">
                <form method="POST" action="{{ route('menu.store') }}">
                    @csrf
                    <x-input type="text" :defaultValue="old('code')" :isRequired="true" globalAttribute="code" label="Code"></x-input>
                    
                    <x-input type="text" :defaultValue="old('name')" :isRequired="true" globalAttribute="name" label="Name"></x-input>
                    
                    <x-select globalAttribute="status" label="Status">
                        @foreach ($status as $stat)
                            <option value="{{ $stat['val'] }}" @if(old('status') == $stat['val']) selected @endif>{{ $stat['name'] }}</option>
                        @endforeach
                    </x-select>
                    
                    <x-select globalAttribute="nav_id" label="Nav ID" customAttribute="required">
                        @foreach ($nav as $navi)
                            <option value="{{ $navi->id }}" @if(old('nav_id') == $navi->id) selected @endif>{{ $navi->name }}</option>
                        @endforeach
                    </x-select>

                    <x-input type="text" :defaultValue="old('icon')" :isRequired="true" globalAttribute="icon" label="Icon"></x-input>
                    
                    <x-input type="number" :defaultValue="old('order')" globalAttribute="order" :isRequired="true" customAttribute="min=0" label="Order" ></x-input>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
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
