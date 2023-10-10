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
                        <div class="row">
                            <div class="col-md-12">
                                <p class="font-weight-bold mt-3">Description</p>
                                <textarea class="textarea ckEditor form-control" rows="15" disabled>{{ $article->descriptions ?? '-' }}</textarea>

                            </div>
                        </div>
                        <div class="border-top border-3 my-4 "></div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="font-weight-bold mt-3">Article Details</p>
                                <table class="table table-bordered table-hover example2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Id</th>
                                            <th>Image</th>
                                            <th>Description</th>
                                            <th>Caption</th>
                                            <th>Url Video</th>
                                            <th>Sort Num</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($article->article_details->sortBy(['sort_num', 'asc']) as $articleDetail)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td>{{ $articleDetail->id }}</td>
                                                <td>
                                                    @php
                                                        $fileExtensions = ['3gp', 'webm', 'wav', 'ogg', 'm4a', 'mp3', 'mp4', 'amr', 'aac', 'pdf', 'avi', 'wmv', 'mpg', 'mov', 'ogm', 'm4v', 'mkv'];
                                                        $imageExtensions = ['png', 'jpg', 'tif', 'gif', 'svg', 'jpeg', 'bmp', 'tiff'];
                                                        $explodeImage = explode('.', $articleDetail->galleries->first()->image ?? '');
                                                        $extension = end($explodeImage);
                                                    @endphp
                                                    @if (!empty($articleDetail->galleries) && !empty($articleDetail->galleries->first()->image))
                                                        @if (in_array($extension, $imageExtensions))
                                                            <img src="{{ asset('img/' . $articleDetail->galleries->first()->image) }}"
                                                                alt="none" style="max-width: 50px;"
                                                                onclick="return openModal('{{ asset('img/' . $articleDetail->galleries->first()->image) }}');">
                                                        @endif
                                                        @if (in_array($extension, $fileExtensions))
                                                            @include('partial.fileDownload', [
                                                                'fileName' =>
                                                                    $articleDetail->galleries->first()->image ??
                                                                    '-',
                                                            ])
                                                        @endif
                                                    @endif
                                                    @if (empty($articleDetail->galleries->first()->image))
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <textarea class="textarea ckEditor form-control" rows="15" disabled>{{ $articleDetail->description ?? '-' }}</textarea>
                                                </td>
                                                <td>{{ $articleDetail->caption ?? '-' }}</td>
                                                <td>
                                                    <x-link-button url="{{ $articleDetail->url }}" />
                                                </td>
                                                <td>{{ $articleDetail->sort_num ?? '-' }}</td>
                                                <td>{{ $articleDetail->status == 0 ? 'Unpublished' : 'Published' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partial.modalImage')
@endsection
@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script> --}}

    {{-- CkEditor --}}
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>

    <script>
        $('.example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "stateSave": true,
            "bDestroy": true,
            "columnDefs": [{
                "targets": '_all',
                "className": 'dt-body-justify'
            }],
            "columnDefs": [{
                "targets": 2,
                "orderable": false
            }],
            "columnDefs": [{
                "targets": 3,
                "width": "60%",
            }]
        });

        $(document).ready(function() {
            // $('.summernote').summernote({
            //     toolbar: [
            //         ['view', ['fullscreen']],
            //     ],
            //     tabsize: 2,
            //     height: 100
            // }).next().find(".note-editable").attr("contenteditable", false);

            $('.ckEditor').ckeditor({
                removeButtons: "Source,Save,NewPage,Print,ExportPdf,Templates,Cut,Copy,PasteText,Paste,Redo,Replace,Undo,Find,About,TextColor,BGColor,Styles,Format,Font,FontSize,SpecialChar,HorizontalRule,Image,Table,Link,Anchor,PageBreak,Iframe,Smiley,ShowBlocks,SelectAll,Scayt,HiddenField,Checkbox,Form,TextField,Textarea,Select,Button,ImageButton,Bold,Subscript,Superscript,Strike,Italic,CopyFormatting,RemoveFormat,Indent,Outdent,CreateDiv,JustifyCenter,JustifyRight,JustifyBlock,JustifyLeft,Blockquote,NumberedList,BulletedList,Underline,BidiLtr,BidiRtl,Language,Unlink,PasteFromWord,Radio"
            });
        });
    </script>
@endpush
