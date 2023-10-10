<button type="button" class="btn btn-info btn-sm" data-target="#exampleModalScrollable{{ $article->id }}"
    data-toggle="modal">
    <i class='fa fa-eye'></i>
</button>
<div class="modal fade" id="exampleModalScrollable{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered modal-dialog-scrollable " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">{{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- @php
                    $data = \App\Models\ArticleDetail::where('articles_id', '=', $articleDetails)->get();
                @endphp --}}
                <p></p>
                <table class="table table-bordered table-hover example2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Description</th>
                            <th>Caption</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articleDetails as $articleDetail)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>{{ $articleDetail->description }}</td>
                                <td>{{ $articleDetail->caption }}</td>
                                <td>{{ $articleDetail->status === 0 ? 'Unpublished' : 'Published' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    // function openModalData(data) {
    //     console.log(data)
    //     var modalDetail = $('.test');
    //     modalDetail.attr('p', data);
    //     $data1 = JSON.stringify(data)
    //     $('#exampleModalScrollable').modal('toggle');
    // }


    $('.example2').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        stateSave: true,
        "bDestroy": true,
        columnDefs: [{
            targets: '_all',
            className: 'dt-body-justify'
        }]
    });
</script>
