<div id="basic-pills-wizard" class="twitter-bs-wizard">
    <ul class="twitter-bs-wizard-nav">
        <li class="nav-item">
            <a href="#seller-details" class="nav-link" data-toggle="tab">
                <span class="step-number">01</span>
                <span class="step-title">Register Pemohon</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#company-document" class="nav-link" data-toggle="tab">
                <span class="step-number">02</span>
                <span class="step-title">Kontak Person</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="#bank-detail" class="nav-link" data-toggle="tab">
                <span class="step-number">03</span>
                <span class="step-title">Penandatangan</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="#confirm-detail" class="nav-link" data-toggle="tab">
                <span class="step-number">04</span>
                <span class="step-title">Yang Diberi Kuasa</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="#dokumen-pendukung" class="nav-link" data-toggle="tab">
                <span class="step-number">05</span>
                <span class="step-title">Dokumen Pendukung</span>
            </a>
        </li>
    </ul>
    <div class="tab-content twitter-bs-wizard-tab-content">
        <div class="tab-pane" id="seller-details">
            <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
            <label for="" class="form-label fw-bold h6 mt-0 mb-0">Registrasi Pemohon</label>
            <hr class="mt-0 mb-3">

            <form class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="row mb-3">
                        <label for="upt" class="col-sm-3 col-form-label">UPT</label>
                        <div class="col-sm-9">
                            <select class="select2 form-control upt-select" type="text" id="upt" multiple
                                name="upt" data-placeholder="select item">
                                <select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 col-form-label">Jenis
                            Identitas</label>
                        <div class="col">
                            <select class="form-control select-item" type="text" placeholder="Jenis Identitas"
                                id="jenis_identitas" name="jenis_identitas">
                                <option value="">select item</option>
                                <option value="PASSPORT">PASSPORT</option>
                                <option value="KTP">KTP</option>
                                <option value="NPWP">NPWP 16 DIGIT</option>
                            </select>
                        </div>
                        <div class="col">
                            <input class="form-control" type="text" placeholder="Nomer Identitas"
                                id="nomer_identitas" name="nomer_identitas">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="upt" class="col-sm-3 col-form-label">Telephone</label>
                        <div class="col-sm-9">
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">62</span>
                                <input type="text" class="form-control" id="validationCustomUsername"
                                    aria-describedby="inputGroupPrepend" required>

                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="fax" class="col-sm-3 col-form-label">Fax</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" placeholder="Fax" id="fax" name="fax">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="email" placeholder="Email" id="email"
                                name="email">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lingkup_akivitas" class="col-sm-3 col-form-label">Lingkup Akivitas</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="lingkup_akivitas" rows="3"
                                placeholder="Lingkup Akivitas" name="lingkup_akivitas">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status_import" class="col-sm-3 col-form-label">Status Import</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="status_import" name="status_import">
                                <option selected disabled>Pilih Status</option>
                                <option value="import">Import</option>
                                <option value="tidak_import">Tidak Import</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" placeholder="Alamat" id="alamat"
                                name="alamat">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="negara" class="col-sm-3 col-form-label">Negara</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" placeholder="Negara" id="negara"
                                name="negara">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="provinsi" class="col-sm-3 col-form-label">Provinsi</label>
                        <div class="col-sm-9">
                            <select class="form-control provinsi-select" type="text" placeholder="Provinsi"
                                id="provinsi" name="provinsi"></select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kota" class="col-sm-3 col-form-label">Kota</label>
                        <div class="col-sm-9">
                            <select class="form-control kota-select" type="text" placeholder="Kota"
                                id="kota" name="kota"></select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kecamatan" class="col-sm-3 col-form-label">Kecamatan</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" placeholder="Kecamatan" id="kecamatan"
                                name="kecamatan">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane" id="company-document">
            <div>
                <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                <label for="" class="form-label fw-bold h6 mt-0 mb-0">Kontak Person</label>
                <hr class="mt-0 mb-3">
                <form>
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Nama" id="nama"
                                name="nama">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Alamat" id="alamat"
                                name="alamat">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="tel" placeholder="Telepon" id="telepon"
                                name="telepon">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane" id="bank-detail">
            <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
            <label for="" class="form-label fw-bold h6 mt-0 mb-0">Penandatangan</label>
            <hr class="mt-0 mb-3">
            <div>
                <form>
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Nama" id="nama"
                                name="nama">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jenis_identitas" class="col-sm-2 col-form-label">Jenis Identitas</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Jenis Identitas"
                                id="jenis_identitas" name="jenis_identitas">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Jabatan" id="jabatan"
                                name="jabatan">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Alamat" id="alamat"
                                name="alamat">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- <div class="tab-pane" id="confirm-detail">
            <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
            <label for="" class="form-label fw-bold h6 mt-0 mb-0">Yang Diberi Kuasa</label>
            <hr class="mt-0 mb-3">
            <div>
                <div class="row mb-4">
                    <div class="d-flex justify-content-start mb-5">
                        <label class="col-md-4 col-sm-7">Apakah terdapat yang diberi kuasa?</label>
                        <div class="col-md-1 col-sm-2 ms-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kuasa" id="kuasa_ya"
                                    value="ya">
                                <label class="form-check-label" for="kuasa_ya">
                                    Ya
                                </label>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-2 ms-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kuasa" id="kuasa_tidak"
                                    value="tidak">
                                <label class="form-check-label" for="kuasa_tidak">
                                    Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="form-kuasa">
                        <div class="row mb-4">
                            <label for="status_import" class="col-sm-2 col-form-label">Jenis Perusahaan</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="status_import" name="status_import">
                                    <option selected disabled>Pilih Item</option>
                                    <option value="import">PPJK</option>
                                    <option value="tidak_import">EMKL</option>
                                    <option value="tidak_import">EMKU</option>
                                    <option value="tidak_import">Lainya</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-4 col-sm-2">
                                <button class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop">Tambah PPJK/EMKL/EMKU</button>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <table id="datatable-kuasa" class="table table-bordered dt-responsive nowrap"
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
                                    <tr>
                                        <td>Colleen Hurst</td>
                                        <td>Javascript Developer</td>
                                        <td>San Francisco</td>
                                        <td>39</td>
                                        <td>2009/09/15</td>
                                        <td>$205,500</td>
                                    </tr>
                                    <tr>
                                        <td>Sonya Frost</td>
                                        <td>Software Engineer</td>
                                        <td>Edinburgh</td>
                                        <td>23</td>
                                        <td>2008/12/13</td>
                                        <td>$103,600</td>
                                    </tr>
                                    <tr>
                                        <td>Sonya Frost</td>
                                        <td>Software Engineer</td>
                                        <td>Edinburgh</td>
                                        <td>23</td>
                                        <td>2008/12/13</td>
                                        <td>$103,600</td>
                                    </tr>
                                    <tr>
                                        <td>Sonya Frost</td>
                                        <td>Software Engineer</td>
                                        <td>Edinburgh</td>
                                        <td>23</td>
                                        <td>2008/12/13</td>
                                        <td>$103,600</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div> --}}
        <div class="tab-pane" id="dokumen-pendukung">
            <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
            <label for="" class="form-label fw-bold h6 mt-0 mb-0">Dokumen Pendukung</label>
            <hr class="mt-0 mb-3">
            <div>
                <form class="row" id="form-pendukung" novalidate>
                    <div class="col-md-4 mb-3">
                        <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                        <select type="text" class="form-select form-control-dokumen" id="jenis_dokumen"
                            name="jenis_dokumen">
                            <option value="">select item</option>
                            <option value="KTP">KTP</option>
                            <option value="PASSPORT">PASSPORT</option>
                            <option value="NPWP">NPWP 16 DIGIT</option>
                        </select>
                        <div class="invalid-feedback" id="jenis_dokumen-feedback"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="nomer_dokumen" class="form-label">Nomer Dokumen</label>
                        <input type="number" class="form-control form-control-dokumen" id="nomer_dokumen"
                            name="nomer_dokumen">
                        <div class="invalid-feedback" id="nomer_dokumen-feedback"></div>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_terbit" class="form-label">Tanggal terbit</label>
                        <input type="date" class="form-control form-control-dokumen" id="tanggal_terbit"
                            name="tanggal_terbit">
                        <div class="invalid-feedback" id="tanggal_terbit-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Upload Dokumen</label>
                        <input type="file" class="form-control" id="file_dokumen" name="file_dokumen" />
                        <div class="invalid-feedback" id="file_dokumen-feedback"></div>
                    </div>
                </form>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-info mb-3" id="button-pendukung">tambah</button>
                </div>
                <div class="row mb-5">
                    <div class="table-responsive">
                        <table id="datatable-dokumen-pendukung" data-pre-register="{{ $register->id }}"
                            class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Dokumen</th>
                                    <th>No Dokumen</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Dokumen</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <ul class="pager wizard twitter-bs-wizard-pager-link">
        <li class="previous"><a href="javascript: void(0);">Previous</a></li>
        <li class="next"><a href="javascript: void(0);">Next</a></li>
        <li class="submit-form d-none bg-success"><a class="bg-success" href="javascript: void(0);">Submit</a></li>
    </ul>
</div>


<!-- Modal -->
{{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                        <label for="" class="form-label fw-bold h6 mt-0 mb-0">Registrasi PPJK</label>
                        <hr class="mt-0 mb-3">
                        <form>
                            <div class="row mb-3">
                                <label for="jenis_identitas" class="col-md-3 col-form-label">Jenis Identitas</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" placeholder="Jenis Identitas"
                                        id="jenis_identitas" name="jenis_identitas">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-md-3 col-form-label">Nama</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" placeholder="Nama" id="nama"
                                        name="nama">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-3 col-form-label">Email</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="email" placeholder="Email" id="email"
                                        name="email">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tanggal_kerjasama" class="col-md-3 col-form-label">Tanggal
                                    Kerjasama</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="date" placeholder="Tanggal Kerjasama"
                                        id="tanggal_kerjasama" name="tanggal_kerjasama">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="alamat" class="col-md-3 col-form-label">Alamat</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" placeholder="Alamat" id="alamat"
                                        name="alamat">
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
                    <div class="col-md-6 col-sm-12">
                        <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                        <label for="" class="form-label fw-bold h6 mt-0 mb-0">Kontak Person PPJK</label>
                        <hr class="mt-0 mb-3">
                        <form>
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Nama" id="nama"
                                        name="nama">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Alamat" id="alamat"
                                        name="alamat">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="tel" placeholder="Telepon" id="telepon"
                                        name="telepon">
                                </div>
                            </div>
                        </form>
                        <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                        <label for="" class="form-label fw-bold h6 mt-0 mb-0">Penandatangan</label>
                        <hr class="mt-0 mb-3">
                        <form>
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Nama" id="nama"
                                        name="nama">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="jenis_identitas" class="col-sm-2 col-form-label">Jenis Identitas</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Jenis Identitas"
                                        id="jenis_identitas" name="jenis_identitas">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Jabatan" id="jabatan"
                                        name="jabatan">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Alamat" id="alamat"
                                        name="alamat">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
                        <label for="" class="form-label fw-bold h6 mt-0 mb-0">Dokumen Pendukung</label>
                        <hr class="mt-0 mb-3">
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
                                <tr>
                                    <td>Colleen Hurst</td>
                                    <td>Javascript Developer</td>
                                    <td>San Francisco</td>
                                    <td>39</td>
                                    <td>2009/09/15</td>
                                    <td>$205,500</td>
                                </tr>
                                <tr>
                                    <td>Sonya Frost</td>
                                    <td>Software Engineer</td>
                                    <td>Edinburgh</td>
                                    <td>23</td>
                                    <td>2008/12/13</td>
                                    <td>$103,600</td>
                                </tr>
                                <tr>
                                    <td>Sonya Frost</td>
                                    <td>Software Engineer</td>
                                    <td>Edinburgh</td>
                                    <td>23</td>
                                    <td>2008/12/13</td>
                                    <td>$103,600</td>
                                </tr>
                                <tr>
                                    <td>Sonya Frost</td>
                                    <td>Software Engineer</td>
                                    <td>Edinburgh</td>
                                    <td>23</td>
                                    <td>2008/12/13</td>
                                    <td>$103,600</td>
                                </tr>

                            </tbody>
                        </table>

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
</div> --}}

<script src="{{ asset('assets/js/pages/form-wizard.init.js') }}"></script>
<script src="{{ asset('assets/js/page/form/perorangan.js') }}"></script>
<script></script>
<script>
    UptSelect()
    ProvinsiSelect()
    KotaSelect()
    $('.select-item').select2({
        placeholder: 'select item',
        minimumResultsForSearch: -1,
    })
</script>
