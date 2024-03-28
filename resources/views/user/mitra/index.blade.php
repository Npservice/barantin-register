@extends('layouts.vertical.master')
@section('title', 'Mitra')
@push('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">MITRA</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                            <li class="breadcrumb-item active">Mitra</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm"
                                onclick="modal('Tambah Mitra','modal-xl','static','{{ route('barantin.mitra.create') }}')"">tambah
                                mitra</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user-mitra-datatable" class="table table-bordered dt-responsive nowrap w-100"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Opsi</th>
                                        <th>Nama Mitra</th>
                                        <th>Jenis Identitas</th>
                                        <th>Nomor Identitas</th>
                                        <th>Telepon Mitra</th>
                                        <th>Negara</th>
                                        <th>Provinsi</th>
                                        <th>Kota/Kab</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>

@endsection
@push('js')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
@endpush
@push('custom-js')
    <script src="{{ asset('assets/js/page/select.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatable/user/mitra.js') }}"></script>
@endpush
