<!doctype html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.architect.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            @yield('content')
        </div>
    </div>
    <!--SCRIPTS INCLUDES-->
    @include('layouts.architect.scripts')
</body>

</html>
