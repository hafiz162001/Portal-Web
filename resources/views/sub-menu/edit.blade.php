@extends('layouts.admin-lte.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', ['title' => 'Edit SubMenu', 'route' => 'sub-menu'])

                    <div class="card-body">
                        <form method="POST" action="{{ route('sub-menu.update', $subMenu->id) }}">
                            @csrf
                            @method('put')
                            <x-input type="text" :defaultValue="$subMenu->code" :isRequired="true" globalAttribute="code" label="Code">
                            </x-input>

                            <x-input type="text" :defaultValue="$subMenu->name" :isRequired="true" globalAttribute="name"
                                label="Name"></x-input>

                            <x-select globalAttribute="status" label="Status">
                                @foreach ($status as $stat)
                                    <option value="{{ $stat['val'] }}" @if ($subMenu->status == $stat['val']) selected @endif>
                                        {{ $stat['name'] }}</option>
                                @endforeach
                            </x-select>

                            <x-select globalAttribute="menu_id" label="Menu ID" customAttribute="required">
                                @foreach ($menu as $mn)
                                    <option value="{{ $mn->id }}" @if ($subMenu->menu_id == $mn->id) selected @endif>
                                        {{ $mn->name }}</option>
                                @endforeach
                            </x-select>

                            <x-input type="text" :defaultValue="$subMenu->path" :isRequired="true" globalAttribute="path"
                                label="Path"></x-input>

                            <x-input type="number" :defaultValue="$subMenu->order" globalAttribute="order" customAttribute="min=0"
                                label="Order"></x-input>

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
