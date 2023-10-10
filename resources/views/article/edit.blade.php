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
         .form-check-input[type="checkbox"] {
        width: 1.7em;
        height: 1.7em;
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
                                <div class="col-md-8 px-3">
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="$article->title" :isRequired="true"
                                            globalAttribute="title" label="title*"></x-input>

                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="$article->slug" :isRequired="true"
                                            globalAttribute="slug" label="slug*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="$article->publisher" :isRequired="true"
                                            globalAttribute="publisher" label="publisher*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="$article->creator" :isRequired="true"
                                            globalAttribute="creator" label="creator*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="$article->sumber_gambar" :isRequired="true"
                                            globalAttribute="sumber_gambar" label="sumber_gambar*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="$article->keterangan_gambar" :isRequired="true"
                                            globalAttribute="keterangan_gambar" label="keterangan_gambar*"></x-input>
                                    </div>
                                    <input type="hidden" name="category_name" class="category_name" id="category_name" value="{{ $article->category_name }}">
                                    <input type="hidden" name="channel_name" class="channel_name" id="channel_name" value="{{ $article->channel_name }}">
                                </div>
                                <div class="col-md-4 px-3">
                                    <div class="row">
                                     <div class="form-group">
                                         <label>Tag Opsi:</label>

                                        @php
                                            $article_tag = $article->tag_id;
                                            $strTag = explode(",",$article_tag);

                                        @endphp
                                         @foreach ($tags as $tag)
                                         <div class="form-check mt-2">
                                            <input type="checkbox" class="form-check-input checkbox-option mr-5"
                                            name="tag[]" value="{{ $tag['id'] }}"
                                            @if(in_array($tag['id'], $strTag))
                                                checked="checked"
                                            @endif>

                                            <label class="form-check-label ml-5 mt-2">{{ $tag['name'] }}</label>
                                        </div>

                                     @endforeach
                                       </div>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-select :isStack="1" globalAttribute="category" label="Category">
                                            @foreach ($category as $ctg)
                                                <option value="{{ $ctg['id'] }}"
                                                 @if($article->category_id == $ctg['id']) selected @endif>
                                                 {{ $ctg['name'] }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-select :isStack="1" globalAttribute="channel" label="Channel">
                                            @foreach ($channels as $channel)
                                                <option value="{{ $channel['id'] }}"
                                                 @if($article->channel_id == $channel['id']) selected @endif>
                                                 {{ $channel['name'] }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="font-weight-bold mt-3 px-1">Cover</p>
                                    <div class="input-images mx-1 mb-3"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-text-area :isStack="1" :isCkEditor="true" globalAttribute="descriptions"
                                        :defaultValue="$article->descriptions" label="description" />
                                </div>
                            </div>
                            <div class="border-top border-3 my-4 "></div>
                            <div>
                                @foreach ($articleDetails ?? [] as $key => $articleDetail)
                                    <div class="card p-2 mx-1 mb-4 border border-5 border-dark">
                                        <div class="p-2">
                                            <div class="row">
                                                <input type="hidden" value="{{ $articleDetail->id }}"
                                                    id="extra_article_detail[{{ $key }}][article_detail_id]"
                                                    name="extra_article_detail[{{ $key }}][article_detail_id]"></input>
                                                <div class="col-md-12">
                                                    <x-text-area :isStack="1" :isCkEditor="true"
                                                        globalAttribute="extra_article_detail[{{ $key }}][description]"
                                                        :defaultValue="$articleDetail->description" label="description*" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <x-text-area :isStack="1"
                                                        globalAttribute="extra_article_detail[{{ $key }}][caption]"
                                                        :defaultValue="$articleDetail->caption" label="caption"
                                                        customAttribute="maxlength=150" />
                                                </div>
                                                <div class="col-md-6 px-3">
                                                    <x-input type="file" :isStack="1" :defaultValue="$articleDetail->galleries->first()->image ?? ''"
                                                        globalAttribute="extra_article_detail[{{ $key }}][images_article_detail]"
                                                        label="Image" customAttribute="accept=image/*">
                                                        <small
                                                            name="extra_article_detail[{{ $key }}][output-file-text]"></small>
                                                    </x-input>
                                                    <small>* Old file: </small>
                                                    @php
                                                        $fileExtensions = ['3gp', 'webm', 'wav', 'ogg', 'm4a', 'mp3', 'mp4', 'amr', 'aac', 'pdf', 'avi', 'wmv', 'mpg', 'mov', 'ogm', 'm4v', 'mkv'];
                                                        $imageExtensions = ['png', 'jpg', 'tif', 'gif', 'svg', 'jpeg', 'bmp', 'tiff'];
                                                        $explodeImage = explode('.', $articleDetail->galleries->first()->image ?? '');
                                                        $extension = end($explodeImage);
                                                    @endphp
                                                    @if (!empty($articleDetail->galleries->first()->image))
                                                        @if (in_array($extension, $imageExtensions))
                                                            @include('partial.image', [
                                                                'imgName' =>
                                                                    $articleDetail->galleries->first()->image ??
                                                                    '',
                                                            ])
                                                        @endif
                                                        @if (in_array($extension, $fileExtensions))
                                                            @include('partial.fileDownload', [
                                                                'fileName' =>
                                                                    $articleDetail->galleries->first()->image ??
                                                                    '-',
                                                            ])
                                                        @endif
                                                    @endif
                                                    @if (empty($articleDetail->galleries->first()->image))
                                                        -
                                                    @endif
                                                    <small>* Max file size 2 Mb</small>
                                                    <p><small>* Image(Image ratio should be 16:9)</small></p>
                                                    {{-- <small>* Old image: </small>
                                                <img src="{{ asset('img/' . ($articleDetail->galleries->first()->image ?? '')) }}"
                                                    alt="none" style="max-width: 30px;"
                                                    onclick="return openModal('{{ asset('img/' . ($articleDetail->galleries->first()->image ?? '')) }}');">
                                                <small> * Max file size 2 Mb</small>
                                                <p><small>* Image ratio should be 16:9</small></p> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 px-3">
                                                    <x-input :isStack="1" type="number" :defaultValue="$articleDetail->sort_num"
                                                        :isRequired="true"
                                                        globalAttribute="extra_article_detail[{{ $key }}][sort_num]"
                                                        label="Sort Number*" customAttribute="min=1"></x-input>
                                                </div>
                                                <div class="col-md-6 px-3">
                                                    <x-input :isStack="1" type="url" :defaultValue="$articleDetail->url"
                                                        globalAttribute="extra_article_detail[{{ $key }}][url]"
                                                        label="Url Video"></x-input>

                                                </div>
                                            </div>
                                            <div class="row">
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
                                                <div class="col-md-6 px-3">
                                                    <div class="form-group row">
                                                        <label
                                                            for="extra_article_detail[{{ $key }}][status]">Status*</label>
                                                        <select id="extra_article_detail[{{ $key }}][status]"
                                                            class="form-control select-2"
                                                            name="extra_article_detail[{{ $key }}][status]"
                                                            autocomplete="extra_article_detail[{{ $key }}][status]"
                                                            autofocus required>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script> --}}
    {{-- CkEditor --}}
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-2').select2();
            $('.ckEditor').ckeditor();
            $('#type').select2({
                width: '100%',
            });
            var selectedOptions = {!! json_encode($article->tag_id) !!};
        $('.checkbox-option').each(function() {
            if ($.inArray(parseInt($(this).val()), selectedOptions) !== -1) {
                $(this).prop('checked', true);
            }
        });
            let images = <?= json_encode($images) ?>;
            // console.log(images)
            $('.input-images').imageUploader({
                extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
                mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
                maxSize: 2 * 1024 * 1024,
                preloaded: images || [],
            });
            function textToSlug(text) {
                return text
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Hapus karakter yang tidak diinginkan kecuali huruf, angka, spasi, dan tanda "-"
                    .replace(/\s+/g, '-') // Ganti spasi dengan tanda "-"
                    .replace(/--+/g, '-') // Jika terdapat lebih dari satu tanda "-" berurutan, ganti dengan satu tanda "-"
                    .trim(); // Hapus spasi di awal dan akhir teks
            }
            $('#title').on('keyup', function() {

                var inputText = $('#title').val();
                var slug = textToSlug(inputText);

                $('#slug').val(slug);
            });
             // categori name
             $('#category').on('change', function() {
            // Ambil nilai dan teks dari elemen option yang dipilih
            var categoryValue = $(this).val();
            var categoryText = $(this).find('option:selected').text();
            
            // Buat logika untuk mengisi keterangan berdasarkan category
            var keterangan = generateKeterangan(categoryText); // Ganti dengan logika Anda
            
            // Isi nilai keterangan ke input
            $('#category_name').val(keterangan);
            });

            // Fungsi untuk menghasilkan keterangan berdasarkan nama
            function generateKeterangan(nama) {
                // Contoh: Tambahkan kata "Keterangan untuk" diikuti dengan nama
                return nama;
            }
            // channel name
            $('#channel').on('change', function() {
            // Ambil nilai dan teks dari elemen option yang dipilih
            var channelValue = $(this).val();
            var channelText = $(this).find('option:selected').text();
            
            // Buat logika untuk mengisi keterangan berdasarkan channel
            var keterangan = generateKeterangan(channelText); // Ganti dengan logika Anda
            
            // Isi nilai keterangan ke input
            $('#channel_name').val(keterangan);
            });

            // Fungsi untuk menghasilkan keterangan berdasarkan nama
            function generateKeterangan(nama) {
                // Contoh: Tambahkan kata "Keterangan untuk" diikuti dengan nama
                return nama;
            }

            // const articleCategory = <?= json_encode($article->category) ?>;
            // if (articleCategory == 'picu_wujudkan') {
            //     $('#typeSection').removeClass('d-none');
            // }
            // $('#category').on('change', function() {
            //     const category = $(this).val();
            //     $('#type').val('').change();
            //     if (category == 'picu_wujudkan') {
            //         $('#typeSection').removeClass('d-none');
            //     } else {
            //         $('#type').val('').change();
            //         $('#typeSection').addClass('d-none');
            //     }
            // });

            // $('.summernote').summernote({
            //     fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36'],
            //     toolbar: [
            //         ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            //         ['font', ['strikethrough', 'superscript', 'subscript']],
            //         ['fontsize', ['fontsize']],
            //         ['para', ['ul', 'ol', 'paragraph']],
            //         ['height', ['height']],
            //         ['table', ['table']],
            //         ['view', ['fullscreen']],
            //     ],
            //     tabsize: 2,
            //     height: 100
            // }).on("summernote.enter", function(we, e) {
            //     $(this).summernote("pasteHTML", "<br><br>");
            //     e.preventDefault();
            // });



        });
    </script>
@endpush
