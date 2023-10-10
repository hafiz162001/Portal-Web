@php
    $folder = $folder ?? 'img';
@endphp

<a href="{{ asset($folder . '/' . $fileName) }}" target="_blank" class="btn btn-xs  btn-success"> Download </a>
