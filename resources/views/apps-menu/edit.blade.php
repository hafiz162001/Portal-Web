@extends('layouts.admin-lte.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', ['title' => 'Edit Apps Menu', 'route' => 'apps-menu'])

                    <div class="card-body">
                        <form method="POST" action="{{ route('apps-menu.update', $appsMenu->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <x-input type="text" :defaultValue="$appsMenu->name" :isRequired="true" globalAttribute="name" label="Name*">
                            </x-input>

                            <x-input type="text" :defaultValue="$appsMenu->navigate" :isRequired="true" globalAttribute="navigate"
                                label="Navigate*"></x-input>
                            <x-input type="file" :defaultValue="old('file')" globalAttribute="file" label="File*"
                                customAttribute="accept=image/*">
                                @include('partial.image-preview', [
                                    'imageName' => $appsMenu->file,
                                    'inputId' => 'file',
                                    'modalId' => 'modalGambar',
                                ])
                                <small>* Max file size 1 Mb</small>
                            </x-input>

                            <x-input type="number" :defaultValue="$appsMenu->order" globalAttribute="order" :isRequired="true"
                                customAttribute="min=0" label="Order*"></x-input>

                            <x-input type="text" :defaultValue="$appsMenu->code" :isRequired="true" globalAttribute="code"
                                label="Code*"></x-input>
                            <x-select globalAttribute="category" label="Category*">
                                <option value="blocx" @if ($appsMenu->category == 'blocx') selected @endif>Blocx</option>
                                <option value="evoria" @if ($appsMenu->category == 'evoria') selected @endif>Evoria</option>
                            </x-select>
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
