@if (filter_var($imgName, FILTER_VALIDATE_URL))
    <img src="{{ $imgName }}" alt="none" style="max-width: 30px;"
        onclick="return openModal('{{ $imgName }}');">
@endif

@if (!filter_var($imgName, FILTER_VALIDATE_URL))
    <img src="{{ asset('img/' . $imgName) }}" alt="none" style="max-width: 30px;"
        onclick="return openModal('{{ asset('img/' . $imgName) }}');">
@endif
