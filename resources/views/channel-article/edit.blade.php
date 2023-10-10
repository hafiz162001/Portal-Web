@extends('layouts.admin-lte.main')
@section('breadcrumb')
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Edit Article Category',
                        'route' => 'category-article',
                    ])

                    <div class="card-body">
                        <form method="POST" action="{{ route('category-article.update', $channelArticle) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-12 px-3">
                                    <x-input :isStack="1" type="text" :defaultValue="$channelArticle->name" :isRequired="true"
                                        globalAttribute="name" label="name*"></x-input>
                                </div>
                                <div class="col-md-12 px-3">
                                    <x-input :isStack="1" type="text" :defaultValue="$channelArticle->slug" :isRequired="true"
                                        globalAttribute="slug" label="slug*"></x-input>
                                </div>
                                <div class="col-md-12 px-3">
                                    <x-select :isStack="1" globalAttribute="parent_id" label="Parent*"
                                        customAttribute="required">
                                        <option value="">Tidak Ada</option>
                                        @foreach ( $channelArticleAll as $data )
                                        @if ($data->id == $channelArticle->parent_id )
                                        <option selected="true" value="{{$data->id}}">{{$data->name}}</option>
                                        @else
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endif

                                        @endforeach
                                    </x-select>
                                </div>
                                <div class="col-md-12 px-3">
                                    <x-input :isStack="1" type="file" :defaultValue="old('image')" globalAttribute="image"
                                        label="Image*" customAttribute="accept=image/*">
                                        @include('partial.image-preview', [
                                            'imageName' => $channelArticle->image,
                                            'inputId' => 'image',
                                            'modalId' => 'modalGambar',
                                        ])
                                        <small>* Max file size 1 Mb</small>
                                    </x-input>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '.textarea'
            });
        });
    </script>
@endsection
