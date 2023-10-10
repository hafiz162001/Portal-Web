@if(!$isStack)
<div class="form-group row">
    <label for="{{ $globalAttribute }}" class="col-md-4 col-form-label text-md-right">{{ __($label ? ucwords($label) : ucwords($globalAttribute)) }}</label>

    <div class="col-md-6">
        <input id="{{ $globalAttribute }}" type="{{ $type }}" class="form-control @error($globalAttribute) is-invalid @enderror" name="{{ $globalAttribute }}" @if($isRequired) required @endif value="{{ $defaultValue }}" {{ $customAttribute }} autocomplete="{{ $globalAttribute }}" autofocus>
        @error($globalAttribute)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        {{ $slot }}
    </div>
</div>
@else
<div class="form-group row">
    <label for="{{ $globalAttribute }}">{{ __($label ? ucwords($label) : ucwords($globalAttribute)) }}</label>
    <input id="{{ $globalAttribute }}" type="{{ $type }}" class="form-control @error($globalAttribute) is-invalid @enderror" name="{{ $globalAttribute }}" @if($isRequired) required @endif value="{{ $defaultValue }}" {{ $customAttribute }} autocomplete="{{ $globalAttribute }}" autofocus>
    @error($globalAttribute)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    {{ $slot }}
</div>
@endif
