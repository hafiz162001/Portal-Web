@extends('layouts.admin-lte.main')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/image-uploader.min.css') }}">
    <style>
        .button-danger {
            background-color: #f44336;
            border-radius: 6px;
            color: white;
        }

        .button-danger:hover {
            background-color: #d43d32;
            color: white;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Create Article',
                        'route' => 'article',
                    ])
                    <div class="card-body">
                        <form action="{{ route('article.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 px-3">
                                    <x-input :isStack="1" type="text" :defaultValue="old('title')" :isRequired="true"
                                        globalAttribute="title" label="title*"></x-input>
                                    <x-input :isStack="1" type="text" :defaultValue="old('subtitle')" :isRequired="true"
                                        globalAttribute="subtitle" label="subtitle*"></x-input>
                                </div>
                                <div class="col-md-6 px-3">
                                    <div class="">
                                        <x-input :isStack="1" type="text" :defaultValue="old('publisher')" :isRequired="true"
                                            globalAttribute="publisher" label="publisher*"></x-input>
                                        {{-- <x-select :isStack="1" globalAttribute="highlight" label="Highlight*"
                                        customAttribute="required">
                                        <option value="0">Unhighlighted</option>
                                        <option value="1">Highlighted</option>
                                    </x-select> --}}
                                    </div>
                                    <div class="form-group row">
                                        <label for="highlight">Highlight</label>
                                        <select class="form-control" name="highlight" id="highlight" required>
                                            <option value="0">Unhighlighted</option>
                                            <option value="1">Highlighted</option>
                                        </select>
                                    </div>
                                    {{-- <x-select :isStack="1" globalAttribute="category_id" label="Category">
                                        @foreach ($festivalCategories as $festivalCategory)
                                            <option value="{{ $festivalCategory->id }}">{{ $festivalCategory->name }}
                                            </option>
                                        @endforeach
                                        <option value="1">1
                                        </option>
                                    </x-select> --}}
                                </div>
                            </div>
                            <p class="font-weight-bold mt-3 px-1">Cover</p>
                            <div class="input-images px-1 mb-3"></div>
                            {{-- <div class="card p-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-text-area :isStack="1" :isTinyMce="true" globalAttribute="description[]"
                                            :defaultValue="old('description')" label="description*" customAttribute="required" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-area :isStack="1" globalAttribute="caption[]" :defaultValue="old('caption')"
                                            label="caption" />

                                    </div>
                                    <div class="col-md-6 px-3">
                                        <x-input type="file" :isStack="1" :defaultValue="old('images_article_detail[]')" :isRequired="true"
                                            globalAttribute="images_article_detail[]" label="Image*"
                                            customAttribute="accept=image/*">
                                            <small>* Max file size 2 Mb</small>
                                        </x-input>
                                    </div>
                                    <div class="col-md-6 px-3">
                                        <x-select :isStack="1" globalAttribute="status[]" label="Status*"
                                            customAttribute="required">
                                            <option value="1">Published</option>
                                            <option value="0">Unpublished</option>
                                        </x-select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="border-top border-3 my-4 "></div>
                            <div id="extra_article_detail" class="mx-1">
                            </div>
                            <div class="row">
                                <div class="col-md-6 py-3 mx-1">
                                    <button type="button" name="add" id="add_field" class="btn btn-success"><i
                                            class="fa fa-plus"></i> Add
                                        Description
                                        & Caption</button>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col mx-1">
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
@push('scripts')
    //use
    {{-- <script src="https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script src="{{ asset('js/image-uploader.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // setTimeout(function() {
            //     var newScript = document.createElement('script');
            //     newScript.src =
            //         `https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js`;
            //     newScript.referrerpolicy = `origin`
            //     document.body.appendChild(newScript);
            // }, 2000);

            $('.input-images').imageUploader({
                extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
                mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
                maxSize: 2 * 1024 * 1024,
            });
            $("#add_field").click(function(e) {

                let newElement = document.createElement('div')
                // newElement.classList.add('card')
                newElement.className = 'card p-2 mb-4 border border-5 border-dark';
                newElement.innerHTML =
                    `<div class="row p-2 px-1">
                    <div class="col-md-12">
                        <x-text-area :isStack="1" :isTinyMce="true" globalAttribute="description[]" :defaultValue="old('description')"
                            label="description*" customAttribute="required"/>
                    </div>
                    <div class="col-md-6">
                        <x-text-area :isStack="1" globalAttribute="caption[]" :defaultValue="old('caption')"
                            label="caption"/>
                    </div>
                    <div class="col-md-6 px-3">
                        <x-input type="file" :isStack="1" :defaultValue="old('images_article_detail[]')"
                        globalAttribute="images_article_detail[]" label="Image"
                        customAttribute="accept=image/*">
                        </x-input>
                         <small>* Max file size 2 Mb</small>
                        <p><small>* Image ratio should be 16:9</small></p>
                    </div>
                    <div class="col-md-6 px-3">
                        <x-select :isStack="1" globalAttribute="status[]" label="Status*"
                            customAttribute="required">
                            <option value="1">Published</option>
                            <option value="0">Unpublished</option>
                        </x-select>
                    </div>
                    <div class="col-md-12 text-md-right mt-2"><button type="button" class="btn btn-danger" id="remove_article_detail"><i class="fa fa-trash"></i></button></div></div>`
                document.getElementById('extra_article_detail').appendChild(newElement);
                //use
                //     var script = document.createElement('script');
                //     script.innerHTML = `tinymce.init({
            //         selector: '.tinyMCE',
            //         mode: "textareas",
            //     });
            // `;

                // use
                // document.body.appendChild(script);
                // var newScript = document.createElement('script');
                // newScript.src =
                //     `https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js`;
                // newScript.referrerpolicy = `origin`
                // document.body.appendChild(newScript);
                //use
            });
            $(document).on('click', '#remove_article_detail', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent().parent()
                $(row_item).remove();
            })
        });
    </script>
@endpush
