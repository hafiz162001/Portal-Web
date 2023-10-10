<div class="row my-2">
    <div class="col">
        <img id="img" src="{{ asset('img/' . $imageName) }}" style="max-width: 30px;" data-toggle="modal"
            data-target="#{{ $modalId }}">
    </div>
</div>

@push('modal')
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="img-{{ $modalId }}" src="{{ asset('img/' . $imageName) }}" style="max-width: 100%;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@if (empty($useCustomScript))
    @section('javascript')
        <script>
            $(document).ready(function() {
                var modalImg = $('#img-{{ $modalId }}');
                var imgInp = document.getElementById('{{ $inputId }}');
                var blah = document.getElementById('img');
                imgInp.onchange = evt => {
                    // console.log(modalImg)
                    const [file] = imgInp.files
                    if (file) {
                        var url = URL.createObjectURL(file)
                        blah.src = url
                        modalImg.attr('src', url);
                    }
                }
            });
        </script>
    @endsection
@endif
