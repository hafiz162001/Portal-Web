@extends('layouts.admin-lte.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        @include('partial.alert')
        <div class="card">
            @include('partial.cardHeader', ['title'=> "Edit Menu", "route" => "menu"])

            <div class="card-body">
                <form method="POST" action="{{ route('menu.update', $menu->id) }}">
                    @csrf
                    @method('put')
                    <x-input type="text" :defaultValue="$menu->code" :isRequired="true" globalAttribute="code" label="Code"></x-input>
                    
                    <x-input type="text" :defaultValue="$menu->name" :isRequired="true" globalAttribute="name" label="Name"></x-input>
                    
                    <x-select globalAttribute="status" label="Status">
                        @foreach ($status as $stat)
                            <option value="{{ $stat['val'] }}" @if($menu->status == $stat['val']) selected @endif>{{ $stat['name'] }}</option>
                        @endforeach
                    </x-select>
                    
                    <x-select globalAttribute="nav_id" label="Nav ID" customAttribute="required">
                        @foreach ($nav as $navi)
                            <option value="{{ $navi->id }}" @if($menu->nav_id == $navi->id) selected @endif>{{ $navi->name }}</option>
                        @endforeach
                    </x-select>

                    <x-input type="text" :defaultValue="$menu->icon" :isRequired="true" globalAttribute="icon" label="Icon"></x-input>
                    
                    <x-input type="number" :defaultValue="$menu->order" globalAttribute="order" :isRequired="true" customAttribute="min=0" label="Order" ></x-input>

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
