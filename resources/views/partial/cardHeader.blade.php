<div class="card-header with-border">
    <div class="row w-100 justify-content-between">
        <div class="col-auto align-self-center">
            {{ $title }}
        </div>
        <div class="col-auto text-right mr-0 pr-0">
            @if (Route::currentRouteName() != $route . '.index')
                <a href="{{ route($route . '.index') }}" class="btn btn-primary">List Data</a>
            @endif

            @if (Route::currentRouteName() != $route . '.create' && Route::currentRouteName() != $route . '.edit')
                {{-- <a class="btn-transition btn btn-outline-dark" href="{{ route($route . '.create') }}"><i
                        class="fa fa-plus"></i> {{ __('Add') }}</a> --}}
                <a class="btn btn-primary" href="{{ route($route . '.create') }}"><i class="fa fa-plus"></i>
                    {{ __('Add') }}</a>
            @endif
        </div>
    </div>
</div>
