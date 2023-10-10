@if (!$isStack)
    <div class="form-group row">
        <label for="{{ $globalAttribute }}"
            class="col-md-4 col-form-label text-md-right">{{ __(ucwords($label)) }}</label>

        <div class="col-md-6">
            <select id="{{ $globalAttribute }}"
                class="{{ $select2Class }} form-control @error($globalAttribute) is-invalid @enderror"
                name="{{ $globalAttribute }}" {{ $customAttribute }} autocomplete="{{ $globalAttribute }}" autofocus>
                {{ $slot }}
            </select>
            @error($globalAttribute)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
@else
    <div class="form-group row">
        <label for="{{ $globalAttribute }}">{{ __($label ? ucwords($label) : ucwords($globalAttribute)) }}</label>
        <select id="{{ $globalAttribute }}"
            class="{{ $select2Class }} form-control @error($globalAttribute) is-invalid @enderror"
            name="{{ $globalAttribute }}" {{ $customAttribute }} autocomplete="{{ $globalAttribute }}" autofocus>
            {{ $slot }}
        </select>
        @error($globalAttribute)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@endif
