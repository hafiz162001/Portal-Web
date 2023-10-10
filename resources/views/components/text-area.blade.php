@if (!$isStack)
    <div class="form-group row">
        <label for="{{ $globalAttribute }}"
            class="col-md-4 col-form-label text-md-right">{{ __($label ? $label : ucwords($globalAttribute)) }}</label>

        <div class="col-md-6">
            <textarea id="{{ $globalAttribute }}" @if ($isRequired) required @endif
                class="textarea @if ($isTinyMce) tinyMCE @endif @if ($isSummerNote) summernote @endif @if ($isCkEditor) ckEditor @endif form-control @error($globalAttribute) is-invalid @enderror"
                name="{{ $globalAttribute }}" {{ $customAttribute }} autocomplete="{{ $globalAttribute }}" autofocus
                rows="{{ $rows ? $rows : 3 }}">{{ $defaultValue }}</textarea>
            {{ $slot }}
            @error($globalAttribute)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
@else
    <div class="form-group">
        <div class="row">
            <div class="col-12">
                <label for="{{ $globalAttribute }}"
                    class="d-block">{{ __($label ? ucwords($label) : ucwords($globalAttribute)) }}</label>
            </div>
            <div class="col-12">
                <textarea id="{{ $globalAttribute }}" @if ($isRequired) required @endif
                    class="textarea @if ($isTinyMce) tinyMCE @endif @if ($isSummerNote) summernote @endif @if ($isCkEditor) ckEditor @endif form-control @error($globalAttribute) is-invalid @enderror"
                    name="{{ $globalAttribute }}" {{ $customAttribute }} autocomplete="{{ $globalAttribute }}" autofocus
                    rows="{{ $rows ? $rows : 3 }}">{{ $defaultValue }}</textarea>
                @error($globalAttribute)
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
@section('script')
    @if ($isTinyMce)
        <script src="https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: '.tinyMCE',
                init_instance_callback: function(editor) {
                    var freeTiny = document.querySelector('.tox .tox-notification--in');
                    freeTiny.style.display = 'none';
                }
            });
        </script>
    @endif
@endsection
