@extends('layouts.admin-lte.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', [
                        'title' => 'Edit Term & Condition',
                        'route' => 'term-condition',
                    ])
                    <div class="card-body">
                        <form action="{{ route('term-condition.update', $termCondition->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-6 px-3">
                                    <x-input type="text" :isStack="1" :defaultValue="$termCondition->page_nm" globalAttribute="page_nm"
                                        :isRequired="true" label="name*">
                                    </x-input>
                                </div>
                                <div class="col-md-6 px-3">
                                    <x-input type="text" :isStack="1" :defaultValue="$termCondition->page_title" globalAttribute="page_title"
                                        :isRequired="true" label="title*">
                                    </x-input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-text-area globalAttribute="page_content" :isStack="1" :defaultValue="$termCondition->page_content"
                                        :isRequired="true" :isTinyMce="true" customAttribute="required" label="Content*" />
                                </div>
                            </div>
                            <div class="form-group row mt-3 mb-0">
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="https://cdn.tiny.cloud/1/ozc9x14w309wc8eot0igzgly7fpfjmxtln50aaj7fi2mfrj2/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '.tinyMCE',
                init_instance_callback: function(editor) {
                    var freeTiny = document.querySelector('.tox .tox-notification--in');
                    freeTiny.style.display = 'none';
                }
            });
        });
    </script>
@endsection
