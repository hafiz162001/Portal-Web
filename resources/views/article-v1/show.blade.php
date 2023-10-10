@extends('layouts.admin-lte.main')
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    <div class="card-header with-border">
                        <div class="row w-100 justify-content-between">
                            <div class="col-auto align-self-center">
                                Article : <strong>{{ $article->title }}</strong>
                            </div>
                            <div class="col-auto text-right mr-0 pr-0">
                                <a href="{{ route('article' . '.index') }}" class="btn btn-primary">List Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover example2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Caption</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($article->article_details->sortBy(['id', 'asc']) as $articleDetail)
                                    <tr>
                                        <td> {{ $loop->iteration }}</td>
                                        <td>
                                            @if (!empty($articleDetail->galleries) && !empty($articleDetail->galleries->first()->image))
                                                <img src="{{ asset('img/' . $articleDetail->galleries->first()->image) }}"
                                                    alt="none" style="max-width: 50px;"
                                                    onclick="return openModal('{{ asset('img/' . $articleDetail->galleries->first()->image) }}');">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $articleDetail->description ?? '-' }}</td>
                                        <td>{{ $articleDetail->caption ?? '-' }}</td>
                                        <td>{{ $articleDetail->status === 0 ? 'Unpublished' : 'Published' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partial.modalImage')
@endsection
@push('scripts')
    <script>
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
@endpush
