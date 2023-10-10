@if (empty($link))
    {{ '-' }}
@endif
@if (filter_var($link, FILTER_VALIDATE_URL))
    <a href="{{ url($link) }}" class="btn btn-info btn-sm"><i class='fa fa-external-link-alt'></i></a>
@endif

@if (!filter_var($link, FILTER_VALIDATE_URL))
    {{ $link }}
@endif
