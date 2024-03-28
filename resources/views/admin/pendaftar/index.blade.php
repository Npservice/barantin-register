@extends('layouts.vertical.master')
@section('title', 'Pendaftar')
@push('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
    <div class="container-fluid">
        <div id="page-grid"></div>
        <div id="page-index">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Pendaftar</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                <li class="breadcrumb-item active">Pendaftar</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Status Import</label>
                                            <select class="form-control select2" id="filter-status-import">
                                                <option value="all">All Data</option>
                                                <option value="25">Importir Umum</option>
                                                <option value="26">Importir Produsen</option>
                                                <option value="27">Importir Terdaftar</option>
                                                <option value="28">Agen Tunggal</option>
                                                <option value="29">BULOG</option>
                                                <option value="30">PERTAMINA</option>
                                                <option value="31">DAHANA</option>
                                                <option value="32">IPTN</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Register</label>
                                            <input type="text" class="form-control" id="tanggal-register">
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <div class="d-flex justify-content-end">
                                <button class="btn btn-primary btn-sm w-"
                                    onclick="modal('Tambah UPT','modal-xl','static','{{ route('admin.master-upt.create') }}')">tambah
                                    upt</button>
                            </div> --}}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pendaftar-datatable" class="table table-bordered dt-responsive nowrap w-100"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Opsi</th>
                                            <th>Status</th>
                                            <th>Blokir</th>
                                            <th>Nama Perusahaan</th>
                                            <th>Jenis Identitas</th>
                                            <th>Nomor Identitas</th>
                                            <th>Telepon</th>
                                            <th>Fax</th>
                                            <th>Email</th>
                                            <th>Negara</th>
                                            <th>Provinsi</th>
                                            <th>Kota/Kab</th>
                                            <th>Alamat</th>
                                            <th>Status Import</th>
                                            <th>Tgl Register</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
    {{-- modal ditolak only --}}
    <div class="modal fade" id="modal-tolak-keterangan" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Keterangan Ditolak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-tolak">
                        <div class="mb-3">
                            <input type="hidden" name="url" id="url-tolak">
                            <label for="exampleFormControlInput1" class="form-label">keterangan</label>
                            <input type="text" class="form-control" id="keterangan-tolak">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-tolak">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
@endpush
@push('custom-js')
    <script src="{{ asset('assets/js/page/datatable/pendaftar.js') }}"></script>
@endpush
