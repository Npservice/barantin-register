<div id="basic-pills-wizard" class="twitter-bs-wizard">
    <ul class="twitter-bs-wizard-nav">
        <li class="nav-item">
            <a href="#seller-details" class="nav-link" data-toggle="tab">
                <span class="step-number">01</span>
                <span class="step-title">Seller Details</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#company-document" class="nav-link" data-toggle="tab">
                <span class="step-number">02</span>
                <span class="step-title">Company Document</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="#bank-detail" class="nav-link" data-toggle="tab">
                <span class="step-number">03</span>
                <span class="step-title">Bank Details</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#confirm-detail" class="nav-link" data-toggle="tab">
                <span class="step-number">04</span>
                <span class="step-title">Confirm Detail</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#dokumen-pendukung" class="nav-link" data-toggle="tab">
                <span class="step-number">05</span>
                <span class="step-title">Dokumen Pendukung</span>
            </a>
        </li>
    </ul>
    <div class="tab-content twitter-bs-wizard-tab-content">
        <div class="tab-pane" id="seller-details">
            <form>
                <div class="row mb-3">
                    <label for="upt" class="col-sm-2 col-form-label">UPT</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="UPT" id="upt" name="upt">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jenis_identitas" class="col-sm-2 col-form-label">Jenis Identitas</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Jenis Identitas" id="jenis_identitas"
                            name="jenis_identitas">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Alamat" id="alamat" name="alamat">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="negara" class="col-sm-2 col-form-label">Negara</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Negara" id="negara" name="negara">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="provinsi" class="col-sm-2 col-form-label">Provinsi</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Provinsi" id="provinsi"
                            name="provinsi">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="kota" class="col-sm-2 col-form-label">Kota</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Kota" id="kota" name="kota">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="kecamatan" class="col-sm-2 col-form-label">Kecamatan</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Kecamatan" id="kecamatan"
                            name="kecamatan">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="telephone" class="col-sm-2 col-form-label">Telephone</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Telephone" id="telephone"
                            name="telephone">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="fax" class="col-sm-2 col-form-label">Fax</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" placeholder="Fax" id="fax" name="fax">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="email" placeholder="Email" id="email"
                            name="email">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="logo_perusahaan" class="col-sm-2 col-form-label">Logo Perusahaan</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="file" id="logo_perusahaan" name="logo_perusahaan">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="lingkup_akivitas" class="col-sm-2 col-form-label">Lingkup Akivitas</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="lingkup_akivitas" rows="3" placeholder="Lingkup Akivitas"
                            name="lingkup_akivitas"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="status_import" class="col-sm-2 col-form-label">Status Import</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="status_import" name="status_import">
                            <option selected disabled>Pilih Status</option>
                            <option value="import">Import</option>
                            <option value="tidak_import">Tidak Import</option>
                        </select>
                    </div>
                </div>
            </form>


        </div>
        <div class="tab-pane" id="company-document">
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
        <div class="tab-pane" id="confirm-detail">
            <div>
                <div class="row mb-4">
                    <div class="d-flex justify-content-start">
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
                </div>
            </div>
        </div>
        <div class="tab-pane" id="dokumen-pendukung">
            <div>
                <div class="row mb-4">
                    <input type="file" class="form-control" id="thumbnile" name="thumbnile" />
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

<script src="{{ asset('assets/js/pages/form-wizard.init.js') }}"></script>
<script>
    $('#thumbnile').dropify()
</script>
