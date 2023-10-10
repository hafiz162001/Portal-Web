<div>
    @if (!empty($url))
        <a href="{{ url($url) }}" class="btn btn-info btn-md">
            <i class='fa fa-external-link-alt'></i></a>
    @endif
    @if (empty($url))
        -
    @endif
</div>
