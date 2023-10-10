@extends('layouts.admin-lte.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', ['title' => 'Edit Role', 'route' => 'role'])

                    <div class="card-body">
                        <form method="POST" action="{{ route('role.update', $role) }}">
                            @method('put')
                            @csrf
                            <x-input type="text" :defaultValue="$role->code" :isRequired="true" globalAttribute="code" label="Code"
                                customAttribute="style=text-transform:uppercase"></x-input>

                            <x-checkbox globalAttribute="access" label="access">
                                @php
                                    $access = ['view', 'create', 'update', 'delete'];
                                @endphp
                                @foreach ($access as $tmpAcs)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $tmpAcs }}"
                                            name="{{ $tmpAcs }}" value="{{ true }}"
                                            @if ($role->$tmpAcs == 1) checked @endif>
                                        <label class="form-check-label"
                                            for="{{ $tmpAcs }}">{{ strtoupper($tmpAcs) }}</label>
                                    </div>
                                @endforeach
                            </x-checkbox>

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
