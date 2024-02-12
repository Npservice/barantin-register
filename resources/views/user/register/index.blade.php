@extends('layouts.horizontal.master')
@section('title', 'Register')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/twitter-bootstrap-wizard/prettify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/dropify/dist/dropify.min.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row mb-4">

            <div class="col-md-3 col-sm-12">
                <div class="d-flex align-items-center justify-content-start">
                    <select type="text" class="form-select select-kategori">
                        <option selected disabled value>Pilih Kategori</option>
                        <option value="perorangan">Peorangan</option>
                        <option value="perusahaan">Perusahaan</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title my-2">Register Data</h4>
                    </div>
                    <div class="card-body" id="form-data-input">
                        <div class="my-5 text-center">
                            <h3>Silahkan Pilih Kategori</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
    </div> <!-- container-fluid -->
    <div class="text-center d-none my-5" id="spinner">
        <div class="spinner-border " style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ asset('assets/libs/twitter-bootstrap-wizard/prettify.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dist/dropify.min.js') }}"></script>
@endpush
@push('custom-js')
    <script>
        $('.select-kategori').change(function() {
            let value = $(this).val();
            $('#form-data-input').empty();
            $('#spinner').clone().removeClass('d-none').appendTo('#form-data-input');
            $('#form-data-input').load('/user/form/' + value);
        });
    </script>
@endpush
