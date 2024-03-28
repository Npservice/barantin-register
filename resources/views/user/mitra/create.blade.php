 <div class="modal-body">
     <div class="row">
         <div class="col-md-12 col-sm-12">
             <form>
                 <div class="row mb-3">
                     <label for="jenis_identitas" class="col-md-3 col-form-label">Jenis Identitas</label>
                     <div class="col-md-9">
                         <input class="form-control" type="text" placeholder="Jenis Identitas" id="jenis_identitas"
                             name="jenis_identitas">
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
                         <select class="form-control select-item" type="text" placeholder="Jenis Identitas"
                             id="jenis_identitas" name="jenis_identitas">
                             <option value="">select item</option>
                             <option value="PASSPORT">PASSPORT</option>
                             <option value="KTP">KTP</option>
                             <option value="NPWP">NPWP 16 DIGIT</option>
                         </select>
                         <div class="invalid-feedback" id="jenis_identitas-feedback"></div>
                     </div>
                     <div class="col">
                         <input class="form-control" type="number" placeholder="Nomor Identitas" id="nomor_identitas"
                             name="nomor_identitas" value="">
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
                         <input class="form-control" type="text" placeholder="Negara" id="negara" name="negara">
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
                         <input class="form-control" type="text" placeholder="Kabupaten" id="kabupaten"
                             name="kabupaten">
                     </div>
                 </div>

                 <div class="row mb-3">
                     <label for="kabupaten" class="col-md-3 col-form-label">Alamat</label>
                     <div class="col-md-9">
                         <input class="form-control" type="text" placeholder="Kabupaten" id="kabupaten"
                             name="kabupaten">
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
