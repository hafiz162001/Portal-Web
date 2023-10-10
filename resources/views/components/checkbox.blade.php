<div class="form-group row">
    <label for="{{ $globalAttribute }}" class="col-md-4 col-form-label text-md-right">{{ __(ucwords($label)) }}</label>
    
    <div class="col-md-6 align-self-center">
        {{ $slot }}
    </div>
</div>