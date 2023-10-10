@php
    $routeName = $routeName ?? '';
@endphp

@if (empty($routeName))
    <a href="{{ route($route . '.show', $id) }}" class="btn btn-info btn-sm"> <i class='fa fa-eye'></i></a>
@else
    <a href="{{ route($routeName, $id) }}" class="btn btn-info btn-sm"> <i class='fa fa-eye'></i></a>
@endif
