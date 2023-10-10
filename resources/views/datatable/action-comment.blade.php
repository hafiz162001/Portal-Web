@php
    $routeName = $routeName ?? '';
@endphp

@if (empty($routeName))
    <a href="{{ route($route . '.index', ['id' => $id, 'category'=>$category,'type' => $type]) }}" class="btn btn-info btn-sm"><i
            class="fas fa-comment"></i></a>
@else
    <a href="{{ route($routeName, $id) }}" class="btn btn-info btn-sm"><i class="fas fa-comment"></i></a>
@endif
