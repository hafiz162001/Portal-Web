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
                        'title' => 'Create Article',
                        'route' => 'article',
                    ])
                    <div class="card-body">
                        <form action="{{ route('article.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                          <div class="row">
                            <div class="col-md-8 px-3">
                                <div class="row">
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('title')" :isRequired="true"
                                            globalAttribute="title" label="title*"></x-input>

                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('slug')" :isRequired="true"
                                            globalAttribute="slug" label="slug*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('publisher')" :isRequired="true"
                                            globalAttribute="publisher" label="publisher*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('creator')" :isRequired="true"
                                            globalAttribute="creator" label="creator*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('sumber_gambar')" :isRequired="true"
                                            globalAttribute="sumber_gambar" label="sumber gambar*"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('keterangan_gambar')" :isRequired="true"
                                            globalAttribute="keterangan_gambar" label="Keterangan gambar*"></x-input>
                                    </div>
                                    <input type="text" name="category_name" class="category_name" id="category_name">
                                    <input type="hidden" name="channel_name" class="channel_name" id="channel_name">
                                    {{--
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('category_name')" :isRequired="true"
                                        globalAttribute="category_name" label="category_name*" :isReadonly="true"></x-input>
                                    </div>
                                    <div class="col-md-12 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('channel_name')" :isRequired="true" :isReadonly="true"
                                            globalAttribute="channel_name" label="channel_name*" ></x-input>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-md-4 px-3">
                                <div class="row">
                                 <div class="form-group">
                                     <label>Tag Opsi:</label>
                                     @foreach ($tags as $tag)
                                     <div class="form-check mt-2">
                                       <input type="checkbox" class="form-check-input mr-5" name="tag[]" value="{{ $tag['id'] }}" id="tagss" >
                                       <label class="form-check-label ml-5 mt-2" for="tagss">{{ $tag['name'] }}</label>
                                     </div>
                                     @endforeach
                                   </div>
                                   <div class="col-md-12 px-3">
                                       <x-select :isStack="1" globalAttribute="category" label="Category">
                                            <option value=""> - pilih - </option>
                                           @foreach ($category as $categori)
                                               <option value="{{ $categori['id'] }}">{{ $categori['name'] }}
                                               </option>
                                           @endforeach
                                       </x-select>
                                   </div>
                                   <div class="col-md-12 px-3">
                                       <x-select :isStack="1" globalAttribute="channel" label="Channel">
                                        <option value=""> - pilih - </option>
                                           @foreach ($channels as $channel)
                                               <option value="{{ $channel['id'] }}">{{ $channel['name'] }}
                                               </option>
                                           @endforeach
                                       </x-select>
                                   </div>
                                </div>
                             </div>
                          </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="font-weight-bold mt-3">Cover</p>
                                    <div class="input-images mb-3"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-text-area :isStack="1" :isCkEditor="true" globalAttribute="descriptions"
                                        :defaultValue="old('descriptions')" label="description" />
                                </div>
                            </div>
                            <div class="border-top border-3 my-4 "></div>
                            <div id="extra_article_detail" class="mx-1">
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
    <script src="{{ asset('js/image-uploader.min.js') }}"></script>

    {{-- CkEditor --}}
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $(document).ready(function() {
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

        $('forTag').select2({
        theme: 'bootstrap4',
        placeholder : '{{ isset($placeholder) ? $placeholder : "pilih salah satu" }}',
        closeOnSelect : true,
        allowClear : true
        })
        $('.select2-container--bootstrap4 .select2-selection--multiple .select2-search__field ')
        .css('margin-left', '.3rem')
        .css('margin-top', '.35rem');
            $('#type').select2({
                width: '100%',
            });
            $('.ckEditor').ckeditor();

            $('.input-images').imageUploader({
                extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
                mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
                maxSize: 2 * 1024 * 1024,
            });

        });
    </script>
@endpush
