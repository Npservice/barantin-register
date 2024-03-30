  <div class="modal-body">
      <div class="row">
          <div class="col-md-6 col-sm-12">
              <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
              <label for="" class="form-label fw-bold h6 mt-0 mb-0">Registrasi PPJK</label>
              <hr class="mt-0 mb-3">
              <form class="form-data">
                  @method('PATCH')
                  <div class="row mb-3">
                      <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 col-form-label">Jenis
                          Identitas</label>
                      <div class="col">
                          <select class="form-control select-item" type="text" id="jenis_identitas_ppjk"
                              name="jenis_identitas_ppjk">
                              <option value="">select item</option>
                              <option value="PASSPORT">PASSPORT</option>
                              <option value="KTP">KTP</option>
                              <option value="NPWP">NPWP 16 DIGIT</option>

                          </select>
                          <div class="invalid-feedback" id="jenis_identitas_ppjk-feedback"></div>
                      </div>
                      <div class="col">
                          <input class="form-control" type="number" placeholder="Nomor Identitas"
                              id="nomor_identitas_ppjk" name="nomor_identitas_ppjk"
                              value="{{ $data->nomor_identitas_ppjk }}">
                          <div class="invalid-feedback" id="nomor_identitas_ppjk-feedback"></div>

                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="nama" class="col-md-3 col-form-label">Nama</label>
                      <div class="col-md-9">
                          <input class="form-control" type="text" placeholder="Nama" id="nama_ppjk" name="nama_ppjk"
                              value="{{ $data->nama_ppjk }}">
                          <div class="invalid-feedback" id="nama_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="email" class="col-md-3 col-form-label">Email</label>
                      <div class="col-md-9">
                          <input class="form-control" type="email" placeholder="Email" id="email_ppjk"
                              name="email_ppjk" value="{{ $data->email_ppjk }}">
                          <div class="invalid-feedback" id="email_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="tanggal_kerjasama" class="col-md-3 col-form-label">Tgl
                          Kerjasama</label>
                      <div class="col-md-9">
                          <input class="form-control" type="date" placeholder="Tanggal Kerjasama"
                              id="tanggal_kerjasama_ppjk" name="tanggal_kerjasama_ppjk"
                              value="{{ $data->tanggal_kerjasama_ppjk }}">
                          <div class="invalid-feedback" id="tanggal_kerjasama_ppjk-feedback"></div>
                      </div>
                  </div>


                  <div class="row mb-3">
                      <label for="provinsi" class="col-md-3 col-form-label">Provinsi</label>
                      <div class="col-md-9">
                          <select class="form-control provinsi-select" type="text" id="provinsi"
                              name="provinsi"></select>
                          <div class="invalid-feedback" id="provinsi-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="kabupaten" class="col-md-3 col-form-label">Kabupaten/Kota</label>
                      <div class="col-md-9">
                          <select class="form-control kota-select" type="text" id="kabupaten_kota"
                              name="kabupaten_kota"></select>
                          <div class="invalid-feedback" id="kabupaten_kota-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="alamat" class="col-md-3 col-form-label">Alamat</label>
                      <div class="col-md-9">
                          <textarea class="form-control" type="text" placeholder="Alamat" id="alamat_ppjk" name="alamat_ppjk">{{ $data->alamat_ppjk }}</textarea>
                          <div class="invalid-feedback" id="alamat_ppjk-feedback"></div>
                      </div>
                  </div>
              </form>
          </div>

          <div class="col-md-6 col-sm-12">
              <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
              <label for="" class="form-label fw-bold h6 mt-0 mb-0">Kontak Person PPJK</label>
              <hr class="mt-0 mb-3">
              <form class="form-data">
                  <div class="row mb-3">
                      <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                      <div class="col-sm-9">
                          <input class="form-control" type="text" placeholder="Nama" id="nama_cp_ppjk"
                              name="nama_cp_ppjk"value="{{ $data->nama_cp_ppjk }}">
                          <div class="invalid-feedback" id="nama_cp_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                          <textarea class="form-control" type="text" id="alamat_cp_ppjk" name="alamat_cp_ppjk">{{ $data->alamat_cp_ppjk }}</textarea>
                          <div class="invalid-feedback" id="alamat_cp_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="telepon" class="col-sm-3 col-form-label">Telepon</label>
                      <div class="col-sm-9">
                          <input class="form-control" type="tel" id="telepon_cp_ppjk" name="telepon_cp_ppjk"
                              value="{{ $data->telepon_cp_ppjk }}">
                          <div class="invalid-feedback" id="telepon_cp_ppjk-feedback"></div>

                      </div>
                  </div>
              </form>
              <hr style="border-top: 3px solid rgb(119, 59, 3);" class="mb-1" />
              <label for="" class="form-label fw-bold h6 mt-0 mb-0">Penandatangan</label>
              <hr class="mt-0 mb-3">
              <form class="form-data">
                  <div class="row mb-3">
                      <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                      <div class="col-sm-9">
                          <input class="form-control" type="text" placeholder="Nama" id="nama_tdd_ppjk"
                              name="nama_tdd_ppjk" value="{{ $data->nama_tdd_ppjk }}">
                          <div class="invalid-feedback" id="nama_tdd_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="jenis_identitas" class="col-md-3 col-sm-3 col-xs-12 col-form-label">Jenis
                          Identitas</label>
                      <div class="col">
                          <select class="form-control select-item" type="text" id="jenis_identitas_tdd_ppjk"
                              name="jenis_identitas_tdd_ppjk">
                              <option value="">select item</option>
                              <option value="PASSPORT">PASSPORT</option>
                              <option value="KTP">KTP</option>
                              <option value="NPWP">NPWP 16 DIGIT</option>
                          </select>
                          <div class="invalid-feedback" id="jenis_identitas_tdd_ppjk-feedback"></div>
                      </div>
                      <div class="col">
                          <input class="form-control" type="number" id="nomor_identitas_tdd_ppjk"
                              name="nomor_identitas_tdd_ppjk" value="{{ $data->nomor_identitas_tdd_ppjk }}">
                          <div class="invalid-feedback" id="nomor_identitas_tdd_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                      <div class="col-sm-9">
                          <input class="form-control" type="text" placeholder="Jabatan" id="jabatan_tdd_ppjk"
                              name="jabatan_tdd_ppjk" value="{{ $data->jabatan_tdd_ppjk }}">
                          <div class="invalid-feedback" id="jabatan_tdd_ppjk-feedback"></div>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                          <textarea class="form-control" type="text" placeholder="Alamat" id="alamat_tdd_ppjk" name="alamat_tdd_ppjk">{{ $data->alamat_tdd_ppjk }}</textarea>
                          <div class="invalid-feedback" id="alamat_tdd_ppjk-feedback"></div>
                      </div>
                  </div>
              </form>
          </div>

      </div>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      <button type="button" class="btn btn-success" id="button-submit"
          onclick="submit('{{ route('barantin.ppjk.update', $data->id) }}',false)">Simpan</button>
  </div>
  <script>
      ProvinsiSelect('{{ $data->master_provinsi_id }}', true)
      KotaSelect('{{ $data->master_kota_kab_id }}', true)
      $('.select-item').select2({
          placeholder: 'select item',
          minimumResultsForSearch: -1,
          dropdownParent: $('#modal-data'),
      })
      var phoneInput = $('#telepon_cp_ppjk');
      IMask(phoneInput[0], {
          mask: '0000-0000-0000',
          lazy: false
      });
      $('#jenis_identitas_tdd_ppjk').val('{{ $data->jenis_identitas_tdd_ppjk }}').trigger('change');
      $('#jenis_identitas_ppjk').val('{{ $data->jenis_identitas_ppjk }}').trigger('change');
  </script>
