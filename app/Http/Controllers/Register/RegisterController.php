<?php

namespace App\Http\Controllers\Register;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use App\Models\PjBaratanKpp;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use App\Helpers\JsonFilterHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\BarantinApiHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\DokumenPendukungRequestStore;
use App\Http\Requests\RegisterRequesPerorangantStore;
use App\Http\Requests\RegisterRequestPerusahaanIndukStore;
use App\Http\Requests\RegisterRequestPerusahaanCabangStore;

class RegisterController extends Controller
{

    /**
     * Menampilkan indeks formulir registrasi berdasarkan ID pra-registrasi.
     *
     * @param string $id ID dari pra-registrasi yang ingin ditampilkan formulirnya.
     * @return View Mengembalikan tampilan formulir registrasi.
     */
    public function RegisterFormulirIndex(string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $baratan = PjBaratanKpp::where('email', $register->email)->value('id');

        if ($baratan) {
            return view('register.form.index', compact('id', 'baratan'));
        }
        return view('register.form.index', compact('id'));
    }
    /**
     * Menangani permintaan formulir registrasi melalui AJAX.
     *
     * @param Request $request Data permintaan dari pengguna.
     * @param string $id ID dari pra-registrasi.
     * @return View Mengembalikan tampilan yang sesuai berdasarkan jenis pemohon.
     */
    public function RegisterForm(Request $request, string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $baratan_cek = PjBaratanKpp::find($request->baratan_id);
        $baratan = $baratan_cek ?? null;
        if ($register->pemohon === 'perusahaan') {
            if ($register->jenis_perusahaan === 'cabang') {
                $induk = PjBaratin::select('nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'id')->find($register->pj_baratin_id);
                return view('register.form.partial.cabang', compact('register', 'baratan', 'induk'));
            }
            return view('register.form.partial.induk', compact('register', 'baratan'));
        }
        return view('register.form.partial.perorangan', compact('register', 'baratan'));
    }
    /**
     * Menangani permintaan untuk mendapatkan status registrasi.
     * Fungsi ini mengembalikan respons JSON jika permintaan dilakukan melalui AJAX,
     * dan mengembalikan tampilan halaman status jika tidak.
     *
     * @return View|JsonResponse
     */
    public function StatusRegister(): View|JsonResponse
    {
        if (request()->ajax()) {
            $model = Register::with([
                'preregister:nama,id',
                'baratin'
            ])
                ->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'pre_register_id', 'updated_at')
                ->whereNotNull('pj_barantin_id')
                ->whereNotNull('status')
                ->orderBy('created_at', 'DESC');

            return DataTables::eloquent($model)
                ->addColumn('upt', function ($row) {
                    $upt = BarantinApiHelper::getMasterUptByID($row->master_upt_id);
                    return $upt['nama_satpel'] . ' - ' . $upt['nama'];
                })
                ->filterColumn('upt', function ($query, $keyword) {
                    $upt = collect(BarantinApiHelper::getDataMasterUpt()->original);
                    $idUpt = JsonFilterHelper::searchDataByKeyword($upt, $keyword, 'nama_satpel', 'nama')->pluck('id');
                    $query->whereIn('master_upt_id', $idUpt);
                })
                ->addColumn('kota', function ($row) {
                    $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($row->baratin->kota, $row->baratin->provinsi_id);
                    return $kota['nama'];
                })
                ->filterColumn('kota', function ($query, $keyword) {
                    $kota = collect(BarantinApiHelper::getDataMasterKota()->original);
                    $idKota = JsonFilterHelper::searchDataByKeyword($kota, $keyword, 'nama')->pluck('id');
                    $query->whereHas('baratin', fn ($query) => $query->whereIn('kota', $idKota));
                })
                ->addIndexColumn()->toJson();
        }
        return view('register.status.index');
    }
    /**
     * Menampilkan halaman pesan untuk proses registrasi.
     * Fungsi ini mengembalikan view yang berisi pesan-pesan terkait proses registrasi.
     *
     * @return View Mengembalikan view pesan registrasi.
     */
    public function RegisterMessage(): View
    {
        return view('register.message');
    }

    /**
     * Memeriksa status registrasi dan validasi email.
     * Fungsi ini akan menghentikan proses jika email belum terverifikasi atau registrasi masih dalam proses.
     *
     * @param mixed $register Data registrasi yang akan diperiksa.
     * @return RedirectResponse|bool Mengembalikan true jika pemeriksaan berhasil, atau mengarahkan kembali jika terdapat masalah.
     */
    public function CheckRegister(mixed $register): RedirectResponse|bool
    {
        if (!$register || !$register->verify_email) {
            abort(redirect()->route('register.message')->with(['message_token' => 'Email tidak terverifikasi silahkan register ulang']));
        }
        /* ambil dat terbaru untuk pengecekan bahwa status sudah fix */
        $register_cek = Register::where('pre_register_id', $register->id)->orderBy('updated_at', 'DESC')->first();

        if (isset($register_cek)) {
            if ($register_cek->status == 'MENUNGGU' || $register_cek->status == 'DISETUJUI') {
                abort(redirect()->route('register.message')->with(['message_waiting' => 'Data sedang di proses upt yang dipilih atau yang terdaftar sebelumnya']));
            }
        }

        return true;
    }


    /**
     * Menyimpan data registrasi perorangan.
     * Fungsi ini akan memeriksa keberadaan dokumen KTP atau PASSPORT sebelum melanjutkan proses registrasi.
     * Jika dokumen lengkap, data akan diproses dan disimpan.
     * Jika tidak, akan dikembalikan respons dengan pesan kesalahan.
     *
     * @param RegisterRequesPerorangantStore $request Data request yang diterima
     * @param string $id ID pra-registrasi
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreRegisterPerorangan(RegisterRequesPerorangantStore $request, string $id)
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        if ($dokumen->contains('KTP') || $dokumen->contains('PASSPORT')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon'], $data['lingkup_aktifitas']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $id,
                'kota' => $request->kota,
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
            ]);
            $this->SaveRegisterPerusahaanIndukPerorangan($request, $id, $data);
            return response()->json(['status' => true, 'message' => 'Register Perorangan Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen KTP/PASSPORT'], 422);
    }
    /**
     * Menangani proses registrasi untuk perusahaan induk.
     * Fungsi ini akan memeriksa keberadaan dokumen NPWP dan NIB sebelum melanjutkan proses registrasi.
     * Jika dokumen lengkap, data akan diproses dan disimpan.
     * Jika tidak, akan dikembalikan respons dengan pesan kesalahan.
     *
     * @param RegisterRequestPerusahaanIndukStore $request Data request yang diterima
     * @param string $id ID pra-registrasi
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreRegisterPerusahaanInduk(RegisterRequestPerusahaanIndukStore $request, string $id)
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        if ($dokumen->contains('NPWP') && $dokumen->contains('NIB')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon'], $data['nitku']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'nitku' => $request->nitku ?? '000000',
                'nama_alias_perusahaan' => $request->nama_alias_perusahaan,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $id,
                'kota' => $request->kota,
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
            ]);

            $this->SaveRegisterPerusahaanIndukPerorangan($request, $id, $data);
            return response()->json(['status' => true, 'message' => 'Register Perusahaan induk Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NPWP, NIB'], 422);
    }
    /**
     * Menangani penyimpanan data registrasi untuk perusahaan cabang.
     *
     * Fungsi ini akan memeriksa keberadaan dokumen pendukung yang diperlukan dan
     * menggabungkan data yang diperlukan sebelum menyimpannya ke dalam database.
     * Jika dokumen yang diperlukan tidak lengkap, fungsi akan mengembalikan pesan error.
     *
     * @param RegisterRequestPerusahaanCabangStore $request Data request yang diterima
     * @param string $id ID pra-registrasi
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreRegisterPerusahaanCabang(RegisterRequestPerusahaanCabangStore $request, string $id)
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        $induk = PjBaratin::select('nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'id')->find($request->id_induk);
        if ($dokumen->contains('NITKU')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'jenis_identitas' => $induk->jenis_identitas,
                'nomor_identitas' => $induk->nomor_identitas,
                'negara_id' => 99,
                'nama_alias_perusahaan' => $request->nama_alias_perusahaan,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $id,
                'pj_baratin_id' => $induk->id,
                'kota' => $request->kota,
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
            ]);

            $this->SaveRegisterCabang($request, $id, $data);
            return response()->json(['status' => true, 'message' => 'Register Perusahaan cabang Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NITKU'], 422);
    }
    /**
     * Menyimpan data registrasi perusahaan induk perorangan menggunakan transaksi database.
     * Fungsi ini bertanggung jawab untuk membuat entri baru untuk PjBaratin dan memperbarui data pra-registrasi.
     * Selain itu, fungsi ini juga mengelola status registrasi UPT berdasarkan kondisi yang ada.
     *
     * @param Request $request Data request yang diterima
     * @param string $id ID pra-registrasi
     * @param Collection $data Data yang akan disimpan
     */
    public function SaveRegisterPerusahaanIndukPerorangan(Request $request, string $id, $data): void
    {
        DB::transaction(
            function () use ($data, $id, $request) {
                $baratin = PjBaratin::create($data->all());
                PreRegister::find($id)->update(['nama' => $baratin->nama_perusahaan]);
                $register_upt_user = Register::where('pre_register_id', $id)->pluck('master_upt_id')->toArray();
                foreach ($request->upt as $upt) {
                    if (in_array($upt, $register_upt_user)) {
                        $register_upt_user_select = Register::where('pre_register_id', $id)->where('master_upt_id', $upt)->first();
                        if (!$register_upt_user_select->status || $register_upt_user_select->status === 'DITOLAK') {
                            Register::find($register_upt_user_select->id)->update(['pj_barantin_id' => $baratin->id, 'status' => 'MENUNGGU']);
                        }
                    } else {
                        Register::create(['master_upt_id' => $upt, 'pj_barantin_id' => $baratin->id, 'status' => 'MENUNGGU', 'pre_register_id' => $id]);
                    }
                }
                DokumenPendukung::where('pre_register_id', $id)->update(['baratin_id' => $baratin->id, 'pre_register_id' => null]);
            }
        );
        return;
    }

    /**
     * Menyimpan data registrasi cabang menggunakan transaksi database.
     *
     * Fungsi ini bertanggung jawab untuk membuat entri baru untuk cabang Barantin
     * dan memperbarui data pra-registrasi serta mengelola status registrasi UPT.
     *
     * @param Request $request Data request yang diterima
     * @param string $id ID pra-registrasi
     * @param Collection $data Data yang akan disimpan
     */
    public function SaveRegisterCabang(Request $request, $id, $data): void
    {
        DB::transaction(
            function () use ($data, $id, $request) {
                $baratin_cabang = BarantinCabang::create($data->all());
                PreRegister::find($id)->update(['nama' => $baratin_cabang->nama_perusahaan]);
                $register_upt_user = Register::where('pre_register_id', $id)->pluck('master_upt_id')->toArray();

                foreach ($request->upt as $upt) {
                    if (in_array($upt, $register_upt_user)) {
                        $register_upt_user_select = Register::where('pre_register_id', $id)->where('master_upt_id', $upt)->first();
                        if (!$register_upt_user_select->status || $register_upt_user_select->status === 'DITOLAK') {
                            Register::find($register_upt_user_select->id)->update(['barantin_cabang_id' => $baratin_cabang->id, 'status' => 'MENUNGGU']);
                        }
                    } else {
                        Register::create(['master_upt_id' => $upt, 'barantin_cabang_id' => $baratin_cabang->id, 'status' => 'MENUNGGU', 'pre_register_id' => $id]);
                    }
                }
                DokumenPendukung::where('pre_register_id', $id)->update(['barantin_cabang_id' => $baratin_cabang->id, 'pre_register_id' => null]);
            }
        );
        return;
    }

    /**
     * Menyimpan dokumen pendukung ke dalam database.
     *
     * Fungsi ini akan menyimpan file dokumen yang diunggah ke dalam penyimpanan publik
     * dan mencatat detail dokumen tersebut ke dalam database.
     *
     * @param string $id ID dari pra-registrasi
     * @param DokumenPendukungRequestStore $request Data request yang mengandung informasi dokumen
     * @return JsonResponse Respon JSON yang mengindikasikan hasil operasi
     */
    public function DokumenPendukungStore(string $id, DokumenPendukungRequestStore $request): JsonResponse
    {
        $file = Storage::disk('public')->put('file_pendukung/' . $id, $request->file('file_dokumen'));
        $data = $request->only(['jenis_dokumen', 'nomer_dokumen', 'tanggal_terbit']);
        $data = collect($data)->merge(['pre_register_id' => $id, 'file' => $file]);

        $dokumen = DokumenPendukung::create($data->all());

        if ($dokumen) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil ditambah', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal ditambah', 200);
    }

    /**
     * Mengelola data tabel untuk dokumen pendukung.
     *
     * Fungsi ini mengambil data dokumen pendukung berdasarkan ID pra-registrasi
     * dan mengembalikan data tersebut dalam format JSON untuk digunakan dalam DataTables.
     *
     * @param string $id ID dari pra-registrasi
     * @return JsonResponse Respon JSON yang mengandung data dokumen pendukung
     */
    public function DokumenPendukungDataTable(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('pre_register_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'register.form.partial.action_pendukung_datatable')
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }
    /**
     * Menghapus dokumen pendukung dari database dan penyimpanan.
     *
     * Fungsi ini akan menghapus file dokumen pendukung dari penyimpanan publik
     * dan menghapus entri dokumen dari database. Jika operasi berhasil,
     * akan mengembalikan respon sukses, jika gagal akan mengembalikan respon error.
     *
     * @param string $id ID dokumen pendukung yang akan dihapus
     * @return JsonResponse Respon JSON yang mengindikasikan hasil operasi
     */
    public function DokumenPendukungDestroy(string $id): JsonResponse
    {

        $data = DokumenPendukung::find($id);
        Storage::disk('public')->delete($data->file);
        $res = $data->delete();

        if ($res) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil dihapus', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal dihapus', 200);
    }
}
