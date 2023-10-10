@extends('layouts.admin-lte.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', ['title' => 'Create Apps Menu', 'route' => 'apps-menu'])

                    <div class="card-body">
                        <form method="POST" action="{{ route('apps-menu.store') }}" enctype="multipart/form-data">
                            @csrf
                            <x-input type="text" :defaultValue="old('name')" :isRequired="true" globalAttribute="name" label="Name*">
                            </x-input>

                            <x-input type="text" :defaultValue="old('navigate')" :isRequired="true" globalAttribute="navigate"
                                label="Navigate*"></x-input>

                            <x-input type="file" :defaultValue="old('file')" :isRequired="true" globalAttribute="file"
                                label="File*" customAttribute="accept=image/*">
                                @include('partial.image-preview', [
                                    'imageName' => old('file'),
                                    'inputId' => 'file',
                                    'modalId' => 'modalGambar',
                                ])
                                <small>* Max file size 1 Mb</small>
                            </x-input>
                            <x-input type="number" :defaultValue="old('order')" globalAttribute="order" :isRequired="true"
                                customAttribute="min=0" label="Order*"></x-input>


                            <x-input type="text" :defaultValue="old('code')" :isRequired="true" globalAttribute="code"
                                label="Code*"></x-input>
                            <x-select globalAttribute="category" label="Category*">
                                <option value="blocx">Blocx</option>
                                <option value="evoria">Evoria</option>
                            </x-select>
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
