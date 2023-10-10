@php
    $alert = "alert-success";
@endphp
@if (session('success')== false)
    @php
        $alert = "alert-danger";
    @endphp
@endif
@if (session('status'))
    <div class="alert {{ $alert }} alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif