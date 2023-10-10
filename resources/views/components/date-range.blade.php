<div class="form-group">
    <label for="{{ $name }}" class="float-right">{{ $label }}
        @if ($attributes['required'])
            <span class="text-danger"> * </span>
        @endif
    </label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
            </span>
        </div>
        <input type="text" class="form-control float-right" id="{{ $name }}" name="{{ $name }}"
            value="{{ $defaultValue }}">
    </div>
</div>
