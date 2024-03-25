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
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">tamba mitra</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </thead>


                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011/04/25</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>63</td>
                                    <td>2011/07/25</td>
                                    <td>$170,750</td>
                                </tr>
                                <tr>
                                    <td>Ashton Cox</td>
                                    <td>Junior Technical Author</td>
                                    <td>San Francisco</td>
                                    <td>66</td>
                                    <td>2009/01/12</td>
                                    <td>$86,000</td>
                                </tr>
                                <tr>
                                    <td>Cedric Kelly</td>
                                    <td>Senior Javascript Developer</td>
                                    <td>Edinburgh</td>
                                    <td>22</td>
                                    <td>2012/03/29</td>
                                    <td>$433,060</td>
                                </tr>
                                <tr>
                                    <td>Airi Satou</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>33</td>
                                    <td>2008/11/28</td>
                                    <td>$162,700</td>
                                </tr>
                                <tr>
                                    <td>Brielle Williamson</td>
                                    <td>Integration Specialist</td>
                                    <td>New York</td>
                                    <td>61</td>
                                    <td>2012/12/02</td>
                                    <td>$372,000</td>
                                </tr>
                                <tr>
                                    <td>Herrod Chandler</td>
                                    <td>Sales Assistant</td>
                                    <td>San Francisco</td>
                                    <td>59</td>
                                    <td>2012/08/06</td>
                                    <td>$137,500</td>
                                </tr>
                                <tr>
                                    <td>Rhona Davidson</td>
                                    <td>Integration Specialist</td>
                                    <td>Tokyo</td>
                                    <td>55</td>
                                    <td>2010/10/14</td>
                                    <td>$327,900</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
    {{-- modal example mitra --}}
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <form>
                                <div class="row mb-3">
                                    <label for="jenis_identitas" class="col-md-3 col-form-label">Jenis Identitas</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Jenis Identitas"
                                            id="jenis_identitas" name="jenis_identitas">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" readonly value="" type="text" id="pemohon"
                                            name="pemohon">
                                        <div class="invalid-feedback" id="pemohon-feedback"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 col-form-label">Jenis
                                        Identitas</label>
                                    <div class="col">
                                        <select class="form-control select-item" type="text"
                                            placeholder="Jenis Identitas" id="jenis_identitas" name="jenis_identitas">
                                            <option value="">select item</option>
                                            <option value="PASSPORT">PASSPORT</option>
                                            <option value="KTP">KTP</option>
                                            <option value="NPWP">NPWP 16 DIGIT</option>
                                        </select>
                                        <div class="invalid-feedback" id="jenis_identitas-feedback"></div>
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="number" placeholder="Nomor Identitas"
                                            id="nomor_identitas" name="nomor_identitas" value="">
                                        <div class="invalid-feedback" id="nomor_identitas-feedback"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="upt" class="col-sm-3 col-form-label">Telepon</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="telepon" name="telepon"
                                            aria-describedby="inputGroupPrepend" required value="">
                                        <div class="invalid-feedback" id="telepon-feedback"></div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="negara" class="col-md-3 col-form-label">Negara</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Negara" id="negara"
                                            name="negara">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="provinsi" class="col-md-3 col-form-label">Provinsi</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Provinsi" id="provinsi"
                                            name="provinsi">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="kabupaten" class="col-md-3 col-form-label">Kabupaten</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Kabupaten"
                                            id="kabupaten" name="kabupaten">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="kecamatan" class="col-md-3 col-form-label">Kecamatan</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Kecamatan"
                                            id="kecamatan" name="kecamatan">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal --}}
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
