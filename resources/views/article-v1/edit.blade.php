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
                        'title' => 'Edit Article',
                        'route' => 'article',
                    ])

                    <div class="card-body">
                        <form method="POST" action="{{ route('article.update', $article->id) }}" enctype="multipart/form-data"
                            id="form1">
                            @csrf
                            @method('put')
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-md-6 px-3">
                                    <x-input :isStack="1" type="text" :defaultValue="$article->title" :isRequired="true"
                                        globalAttribute="title" label="title*"></x-input>
                                    <x-input :isStack="1" type="text" :defaultValue="$article->subtitle" :isRequired="true"
                                        globalAttribute="subtitle" label="subtitle*"></x-input>
                                </div>
                                <div class="col-md-6 px-3">
                                    {{-- <x-select :isStack="1" globalAttribute="category_id" label="Category">
                                        @foreach ($festivalCategories as $festivalCategory)
                                            <option value="{{ $festivalCategory->id }}">{{ $festivalCategory->name }}
                                            </option>
                                        @endforeach
                                        <option value="1">1
                                        </option>
                                    </x-select> --}}
                                    <x-input :isStack="1" type="text" :defaultValue="$article->publisher" :isRequired="true"
                                        globalAttribute="publisher" label="publisher*"></x-input>
                                    {{-- <x-select :isStack="1" globalAttribute="highlight" label="Highlight*"
                                        customAttribute="required">
                                        <option value="1" @if ($article->highlight == 1) selected @endif>Highlighted
                                        <option value="0" @if ($article->highlight == 0) selected @endif>
                                            Unhighlighted
                                        </option>
                                    </x-select> --}}
                                    <div class="form-group row">
                                        <label for="highlight">Highlight</label>
                                        <select class="form-control" name="highlight" id="highlight" required>
                                            <option value="1" @if ($article->highlight == 1) selected @endif>
                                                Highlighted
                                            <option value="0" @if ($article->highlight == 0) selected @endif>
                                                Unhighlighted
                                            </option>
                                        </select>
                                    </div>
                                    {{-- <x-input :isStack="1" type="hidden" :defaultValue="$articleDetail->id" :isRequired="true"
                                        name="article_detail_id" label="" customAttribute="hidden">
                                    </x-input> --}}
                                    {{-- <input type="hidden" value="{{ $article->id }}" id="article_detail_id"
                                        name="article_detail_id"></input> --}}
                                    {{-- <x-input :isStack="1" type="file" :defaultValue="old('file')" :isRequired="true"
                                        globalAttribute="file" label="file" customAttribute="accept=image/*">
                                        <p id="output-file-text"></p>
                                        <small id="output-file-text"></small>
                                        <small>* Max file size 1 Mb</small>
                                    </x-input> --}}
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <x-text-area :isStack="1" globalAttribute="description" :defaultValue="$articleDetail->description"
                                        label="description" customAttribute="required" />
                                </div>
                                <div class="col-md-6">
                                    <x-text-area :isStack="1" globalAttribute="caption" :defaultValue="$articleDetail->caption"
                                        label="caption" customAttribute="required" />
                                </div>
                                <div class="col-md-6">
                                    <x-select :isStack="1" globalAttribute="status" label="Status"
                                        customAttribute="required">
                                        <option value="1" @if ($articleDetail->status == 1) selected @endif>Published
                                        </option>
                                        <option value="0" @if ($articleDetail->status == 0) selected @endif>Unpublished
                                        </option>
                                    </x-select>
                                </div>
                            </div> --}}
                            <p class="font-weight-bold mt-3 px-1">Cover</p>
                            <div class="input-images mx-1 mb-3"></div>
                            {{-- <div class="card p-2">
                                <div class="row">
                                    <input type="hidden" value="{{ $article->article_details[0]->id }}"
                                        id="article_detail_id[]" name="article_detail_id[]"></input>
                                    <div class="col-md-12">
                                        <x-text-area :isStack="1" globalAttribute="description[]" :defaultValue="$article->article_details[0]->description"
                                            label="description*" customAttribute="required" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-text-area :isStack="1" globalAttribute="caption[]" :defaultValue="$article->article_details[0]->caption"
                                            label="caption" />
                                    </div>
                                    <div class="col-md-6 px-3">
                                        <x-input type="file" :isStack="1" :defaultValue="$article->article_details[0]->galleries->first()->image"
                                            globalAttribute="images_article_detail[]" label="Image*"
                                            customAttribute="accept=image/*">
                                            <img src="{{ asset('img/' . $article->article_details[0]->galleries->first()->image) }}"
                                                alt="none" style="max-width: 30px;"
                                                onclick="return openModal('{{ asset('img/' . $article->article_details[0]->galleries->first()->image) }}');">
                                            <small>* Max file size 2 Mb</small>
                                        </x-input>
                                    </div>
                                    <div class="col-md-6 px-3">
                                        <x-select :isStack="1" globalAttribute="status[]" label="Status*"
                                            customAttribute="required">
                                            <option value="1" @if ($article->article_details[0]->status == 1) selected @endif>
                                                Published
                                            </option>
                                            <option value="0" @if ($article->article_details[0]->status == 0) selected @endif>
                                                Unpublished
                                            </option>
                                        </x-select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="border-top border-3 my-4 "></div>
                            <div>
                                @foreach ($articleDetails ?? [] as $articleDetail)
                                    {{-- @if ($loop->iteration != 1) --}}
                                    {{-- @dd($articleDetail->galleries->first()); --}}
                                    <div class="card p-2 mx-1 mb-4 border border-5 border-dark">
                                        <div class="row p-2">
                                            <input type="hidden" value="{{ $articleDetail->id }}" id="article_detail_id[]"
                                                name="article_detail_id[]"></input>
                                            {{-- <input type="hidden" value="{{ $articleDetail->id }}" id="article_detail_id[]"
                                                name="article_detail_id[]"></input> --}}
                                            <div class="col-md-12">
                                                <x-text-area :isStack="1" globalAttribute="description[]"
                                                    :defaultValue="$articleDetail->description" label="description*" />
                                            </div>
                                            <div class="col-md-6">
                                                <x-text-area :isStack="1" globalAttribute="caption[]"
                                                    :defaultValue="$articleDetail->caption" label="caption" />
                                            </div>
                                            <div class="col-md-6 px-3">
                                                <x-input type="file" :isStack="1" :defaultValue="$articleDetail->galleries->first()->image ?? ''"
                                                    globalAttribute="images_article_detail[]" label="Image"
                                                    customAttribute="accept=image/*">
                                                </x-input>
                                                <small>* Old image: </small>
                                                <img src="{{ asset('img/' . ($articleDetail->galleries->first()->image ?? '')) }}"
                                                    alt="none" style="max-width: 30px;"
                                                    onclick="return openModal('{{ asset('img/' . ($articleDetail->galleries->first()->image ?? '')) }}');">
                                                <small> * Max file size 2 Mb</small>
                                                <p><small>* Image ratio should be 16:9</small></p>
                                            </div>
                                            <div class="col-md-6 px-3">
                                                {{-- <x-select :isStack="1" globalAttribute="status[]" label="Status">
                                                    <option value="1"
                                                        @if ($articleDetail->status == 1) selected @endif>
                                                        Published
                                                    </option>
                                                    <option value="0"
                                                        @if ($articleDetail->status == 0) selected @endif>
                                                        Unpublished
                                                    </option>
                                                </x-select> --}}
                                                <div class="form-group row">
                                                    <label for="status[]">Status</label>
                                                    <select class="form-control" name="status[]" id="status[]" required>
                                                        <option value="1"
                                                            @if ($articleDetail->status == 1) selected @endif>
                                                            Published
                                                        </option>
                                                        <option value="0"
                                                            @if ($articleDetail->status == 0) selected @endif>
                                                            Unpublished
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-md-right mt-2">
                                                <button type="button" class="btn button-danger"
                                                    id="remove_article_detail"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
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
                            {{-- <input type="hidden" name="_method" value="PUT"> --}}
                            {{-- <button type="submit" class="btn btn-primary" id="btn-save">Save</button> --}}
                            <div class="form-group row mb-0">
                                <div class="col mx-1">
                                    <button type="submit" class="btn btn-primary" id="btn-save">
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
    @include('partial.modalImage')
@endsection
@push('scripts')
    {{-- <script src="https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script src="{{ asset('js/image-uploader.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let images = <?= json_encode($images) ?>;
            // console.log(images)
            $('.input-images').imageUploader({
                extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
                mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
                maxSize: 2 * 1024 * 1024,
                preloaded: images || [],
            });

            // use
            // var script = document.createElement('script');
            // script.innerHTML = `tinymce.init({
        //         selector: '.tinyMCE',
        //         mode: "textareas",
        //     });
        // `;
            // document.body.appendChild(script);
            // setTimeout(function() {
            //     var newScript = document.createElement('script');
            //     newScript.src =
            //         `https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js`;
            //     newScript.referrerpolicy = `origin`
            //     document.body.appendChild(newScript);
            // }, 2000);

            $("#add_field").click(function(e) {
                let newElement = document.createElement('div')
                // newElement.classList.add('card')
                newElement.className = 'card p-2 mb-4 border border-5 border-dark';
                newElement.innerHTML =
                    `<div class="row p-2">
                    <div class="col-md-12">
                        <x-text-area :isStack="1" globalAttribute="description[]" :defaultValue="old('description')"
                            label="description*" customAttribute="required" />
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
                //     var script = document.createElement('script');
                //     script.innerHTML = `tinymce.init({
            //         selector: '.tinyMCE',
            //         mode: "textareas",
            //     });
            // `;
                //     document.body.appendChild(script);
                //     var newScript = document.createElement('script');
                //     newScript.src =
                //         `https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js`;
                //     newScript.referrerpolicy = `origin`
                //     document.body.appendChild(newScript);
            });
            $(document).on('click', '#remove_article_detail', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent().parent()
                $(row_item).remove();
            });

            // $("#btn-delete").click(function(e) {
            //     // $("#delete").val('PUT');
            //     alert("dasa");
            //     e.preventDefault()
            //     const form = $(this).parent('form');
            //     form.submit();
            //     $("#form2").submit();
            //     // $("#hideForm").hide();
            //     // console.log("Adadas");
            //     // e.preventDefault()
            //     // const form = $(this).parent();
            //     // if (form.submit()) {
            //     //     alert("sdadasda");
            //     // }
            // })
            // $("#btn-save").click(function(e) {

            //     // e.preventDefault()
            //     // alert($(this).parent().parent('form'));
            //     // const form = $(this).parent().parent();

            //     // form.submit();
            //     $("#delete").val('PUT');
            //     $("#form1").submit();
            // })

        });
    </script>
@endpush
