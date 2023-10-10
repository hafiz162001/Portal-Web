@extends('layouts.admin-lte.main')
@section('style')
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('partial.alert')
                <div class="card">
                    @include('partial.cardHeader', ['title' => 'Create User Apps', 'route' => 'user-apps'])

                    <div class="card-body">
                        <form method="POST" action="{{ route('user-apps.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div id="promo-section">
                                <div class="row">
                                    <div class="col-md-6 px-3">
                                        <x-input :isStack="1" type="text" :defaultValue="old('name')" globalAttribute="name"
                                            label="Name"></x-input>
                                        <x-input :isStack="1" type="date" :defaultValue="old('dob')" globalAttribute="dob"
                                            label="DOB"></x-input>
                                        <x-input :isStack="1" type="number" :defaultValue="old('phone')" globalAttribute="phone"
                                            :isRequired="true" label="Phone*"></x-input>
                                        <x-input :isStack="1" type="email" :defaultValue="old('email')" globalAttribute="email"
                                            :isRequired="true" label="Email*"></x-input>
                                        <x-input :isStack="1" type="number" :defaultValue="old('ktp')" globalAttribute="ktp"
                                            label="KTP"></x-input>
                                    </div>
                                    <div class="col-md-6 px-3">
                                        <x-select :isStack="1" globalAttribute="gender" label="gender">
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender['value'] }}">{{ $gender['text'] }}</option>
                                            @endforeach
                                        </x-select>
                                        <x-select :isStack="1" globalAttribute="role" label="Role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role['value'] }}">{{ $role['text'] }}</option>
                                            @endforeach
                                        </x-select>
                                        <div id="blocSection">
                                            <x-select :isStack="1" globalAttribute="bloc_location_id"
                                                label="Bloc Location">
                                                @foreach ($blocLocations as $blocLocation)
                                                    <option value="{{ $blocLocation->id }}">{{ $blocLocation->name }}
                                                    </option>
                                                @endforeach
                                            </x-select>
                                        </div>
                                        {{-- <x-select :isStack="1" globalAttribute="user_category" label="Category"
                                            customAttribute="required">
                                            @foreach ($userCategories as $userCategory)
                                                <option value="{{ $userCategory['value'] }}">{{ $userCategory['text'] }}
                                                </option>
                                            @endforeach
                                        </x-select> --}}
                                        {{-- <input type="hidden" name="user_category" id="user_category" value="blocx"> --}}
                                        <div id="isRegistered">
                                            <x-select :isStack="1" globalAttribute="isRegistered"
                                                label="Is Registered">
                                                <option value="{{ 1 }}">Yes</option>
                                                <option value="{{ 0 }}">No</option>
                                            </x-select>
                                        </div>
                                        <x-input type="file" :isStack="1" :defaultValue="old('foto')" globalAttribute="foto"
                                            label="photo" customAttribute="accept=image/">
                                            @include('partial.image-preview', [
                                                'imageName' => old('foto'),
                                                'inputId' => 'foto',
                                                'modalId' => 'modalGambar',
                                            ])
                                            <small>* Max file size 1 Mb</small>
                                        </x-input>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col">
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
@push('scripts')
    <script>
        $(document).ready(function() {

        });
        $('#role').on('change', function() {
            const roleVal = $(this).val();
            if (roleVal == 1) {
                $('#blocSection').addClass('d-none');
            } else {
                $('#blocSection').removeClass('d-none');
            }
        });
    </script>
@endpush
