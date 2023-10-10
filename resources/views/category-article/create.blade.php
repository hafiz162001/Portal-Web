@extends('layouts.admin-lte.main')
@section('style')
@endsection
@section('breadcrumb')
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Create Article Category',
                        'route' => 'category-article',
                    ])

                    <div class="card-body">
                        <form action="{{ route('category-article.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 px-3">
                                    <x-input :isStack="1" type="text" :defaultValue="old('name')" :isRequired="true"
                                        globalAttribute="name" label="name*"></x-input>
                                </div>

                                <div class="col-md-12 px-3">
                                    <x-input :isStack="1" type="file" :defaultValue="old('image')"
                                        globalAttribute="image" label="image*" customAttribute="accept=image/*">
                                        @include('partial.image-preview', [
                                            'imageName' => old('image'),
                                            'inputId' => 'image',
                                            'modalId' => 'modalGambar',
                                            'useCustomScript' => true,
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
    <script>
        $(document).ready(function() {
            var modalImg = $('#img-modalGambar');
            var imgInp = document.getElementById('image');
            var blah = document.getElementById('img');
            imgInp.onchange = evt => {
                // console.log(modalImg)
                const [file] = imgInp.files
                if (file) {
                    var url = URL.createObjectURL(file)
                    blah.src = url
                    modalImg.attr('src', url);
                }
            }
        });
    </script>
@endsection
