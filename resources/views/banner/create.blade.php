@extends('layouts.admin-lte.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', ['title' => 'Create Banner', 'route' => 'banner'])
                    
                    <div class="card-body">
                        <form method="POST" action="{{ route('banner.store') }}" enctype="multipart/form-data">
                            @csrf
                            <x-input type="file" :defaultValue="old('file')" :isRequired="false" globalAttribute="file" label="File*"
                                customAttribute="accept=image/*">
                                @include('partial.image-preview', [
                                    'imageName' => old('file'),
                                    'inputId' => 'file',
                                    'modalId' => 'modalGambar',
                                ])
                                <small>* Max file size 1 Mb</small>
                                <p> <small>* Image ratio should be 16:9</small></p>
                            </x-input>
                            <x-input type="number" :defaultValue="old('order')" globalAttribute="order" :isRequired="true"
                                customAttribute="min=0" label="Order*"></x-input>

                            <x-input type="text" :defaultValue="old('link_detail')" :isRequired="true" globalAttribute="link_detail"
                                label="Link Detail*"></x-input>
                            <!-- <x-select globalAttribute="category" :defaultValue="old('category')" label="Category*" customAttribute="required">
                                @foreach ($category as $ctg)
                                <option value="{{ $ctg['id'] }}">{{ $ctg['name'] }}
                                </option>
                            @endforeach
                            </x-select> -->
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
@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            $('#category').select2({
                placeholder: "Choose",
            });
            $('#type').select2({
                placeholder: "Choose",
                width: '100%',
            });
            $('#category').on('change', function() {
                const category = $(this).val();
                $('#type').val('').change();
                if (category == 'blocx') {
                    $('#type').prop("required", true);
                    $('#typeSection').show();
                } else {
                    $('#type').prop("required", false);
                    $('#type').val('').change();
                    $('#typeSection').hide();
                }
            });
        });
    </script> --}}
@endpush
