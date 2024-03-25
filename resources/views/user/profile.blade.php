@extends('layouts.vertical.master')
@section('title', 'Log')
@push('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Profile</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                            <li class="breadcrumb-item active">Profile</li>
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
                            <button class="btn btn-primary btn-sm" onclick="Changeprofile()">Ubah Profile</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-md-6 col-sm-12">
                                <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                                <label for="" class="form-label fw-bold h6 mt-0 mb-0">Data</label>
                                <hr class="mt-0 mb-3">
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Nama Perusahaan</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" disabled value="{{ $data->nama_perusahaan ?? '' }}"
                                            type="text" id="pemohon" name="pemohon">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 col-form-label">Jenis
                                        Identitas</label>
                                    <div class="col">
                                        <input value="{{ $data->jenis_identitas ?? '' }}" disabled
                                            class="form-control select-item" type="text" placeholder="Jenis Identitas">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="number" disabled
                                            value="{{ $data->nomor_identitas ?? '' }}" placeholder="Nomor Identitas"
                                            id="nomor_identitas" name="nomor_identitas">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="upt" class="col-sm-3 col-form-label">Telephon</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="telepon" disabled
                                            value="{{ $data->telepon ?? '' }}" name="telepon"
                                            aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="fax" class="col-sm-3 col-form-label">Fax</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" placeholder="Fax" disabled
                                            value="{{ $data->fax ?? '' }}" id="nomor_fax" name="nomor_fax">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" disabled value="{{ $data->email ?? '' }}"
                                            type="email" placeholder="Email" id="email" name="email">
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label for="status_import" class="col-sm-3 col-form-label">Status Import</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select-item" disabled id="status_import"
                                            name="status_import">
                                            <option value="">select item</option>
                                            <option value="25">Importir Umum</option>
                                            <option value="26">Importir Produsen</option>
                                            <option value="27">Importir Terdaftar</option>
                                            <option value="28">Agen Tunggal</option>
                                            <option value="29">BULOG</option>
                                            <option value="30">PERTAMINA</option>
                                            <option value="31">DAHANA</option>
                                            <option value="32">IPTN</option>
                                        </select>
                                        <div class="invalid-feedback" id="status_import-feedback"></div>
                                    </div>
                                </div>
                                <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                                <label for="" class="form-label fw-bold h6 mt-0 mb-0">Alamat</label>
                                <hr class="mt-0 mb-3">
                                <div class="row mb-3">
                                    <label for="negara" class="col-sm-3 col-form-label">Negara</label>
                                    <div class="col-sm-9">
                                        <input class="form-control negara-select" type="text" placeholder="Negara"
                                            id="negara" name="negara" disabled
                                            value="{{ $data->negara->nama ?? '' }}">
                                    </div>
                                </div>

                                <div class="row mb-3" id="provinsi-form">
                                    <label for="provinsi" class="col-sm-3 col-form-label">Provinsi</label>
                                    <div class="col-sm-9">
                                        <input class="form-control provinsi-select" disabled
                                            value="{{ $data->provinsi->nama ?? ('' ?? '') }}" type="text"
                                            placeholder="Provinsi" id="provinsi" name="provinsi">
                                    </div>
                                </div>

                                <div class="row mb-3" id="kota-form">
                                    <label for="kota" class="col-sm-3 col-form-label">Kota/Kab</label>
                                    <div class="col-sm-9">
                                        <input class="form-control provinsi-select" disabled
                                            value="{{ $data->kotas->nama ?? '' }}" type="text" placeholder="Provinsi"
                                            id="provinsi" name="provinsi">
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-9">
                                        <input class="form-control provinsi-select" disabled
                                            value="{{ $data->alamat ?? '' }}" type="text" placeholder="Provinsi"
                                            id="provinsi" name="provinsi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                                <label for="" class="form-label fw-bold h6 mt-0 mb-0">Penandatangan</label>
                                <hr class="mt-0 mb-3">
                                <div>
                                    <div class="row mb-3">
                                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                        <div class="col-sm-9">
                                            <input class="form-control provinsi-select" disabled
                                                value="{{ $data->nama_tdd ?? '' }}" type="text"
                                                placeholder="Provinsi" id="provinsi" name="provinsi">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="jenis_identitas" class="col-sm-3 col-form-label">Jenis
                                            Identitas</label>
                                        <div class="col">
                                            <input class="form-control provinsi-select" disabled
                                                value="{{ $data->jenis_identitas_tdd ?? '' }}" type="text"
                                                placeholder="Provinsi" id="provinsi" name="provinsi">
                                        </div>
                                        <div class="col">
                                            <input class="form-control provinsi-select" disabled
                                                value="{{ $data->nomor_identitas_tdd ?? '' }}" type="text"
                                                id="provinsi" name="provinsi">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                                        <div class="col-sm-9">
                                            <input class="form-control provinsi-select" disabled
                                                value="{{ $data->jabatan_tdd ?? '' }}" type="text"
                                                placeholder="Provinsi" id="provinsi" name="provinsi">
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <input class="form-control provinsi-select" disabled
                                                value="{{ $data->alamat_tdd ?? '' }}" type="text"
                                                placeholder="Provinsi" id="provinsi" name="provinsi">
                                        </div>
                                    </div>
                                </div>

                                <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                                <label for="" class="form-label fw-bold h6 mt-0 mb-0">Kontak Person</label>
                                <hr class="mt-0 mb-3">

                                <div class="row mb-3">
                                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input class="form-control provinsi-select" disabled
                                            value="{{ $data->nama_cp ?? '' }}" type="text" placeholder="Provinsi"
                                            id="provinsi" name="provinsi">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-9">
                                        <input class="form-control provinsi-select" disabled
                                            value="{{ $data->alamat_cp ?? '' }}" type="text" placeholder="Provinsi"
                                            id="provinsi" name="provinsi">
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <label for="telepon" class="col-sm-3 col-form-label">Telepon</label>
                                    <div class="col-sm-9">
                                        <input class="form-control provinsi-select" disabled
                                            value="{{ $data->telepon_cp ?? '' }}" type="text" placeholder="Provinsi"
                                            id="provinsi" name="provinsi">
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="table-responsive">
                            <table class="table nowarp w-100 table-bordered" id="table-detail-dokumen">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Dokumen</th>
                                        <th>No Dokumen</th>
                                        <th>Tanggal Terbit</th>
                                        <th>Dokumen</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="card-footer">
                <div class="text-end">
                    <a class="btn btn-primary btn-sm me-2"
                        onclick="ConfirmRegister('{{ route('admin.baratin.confirm.register', $register_id) }}', '{{ $data->nama_perusahaan }}')">APROVE</a>
                </div>
            </div> --}}
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    {{-- select 2 --}}
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
@endpush
@push('custom-js')
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endpush
