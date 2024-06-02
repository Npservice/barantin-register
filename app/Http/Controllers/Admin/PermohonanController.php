<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Register;
use App\Models\PjBarantin;
use App\Models\PreRegister;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use App\Helpers\JsonFilterHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\BarantinApiHelper;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $uptPusatId;
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
        $this->uptPusatId = env('UPT_PUSAT_ID', 1000);
    }
    public function index(): View
    {
        return view('admin.permohonan.index');
    }

    /**
     * Menampilkan detail permohonan berdasarkan ID.
     *
     * Metode ini mengambil data permohonan menggunakan ID yang diberikan.
     * Jika pemohon adalah perorangan, tampilkan view perorangan.
     * Jika pemohon adalah perusahaan induk atau tidak memiliki jenis, tampilkan view induk.
     * Jika tidak, tampilkan view cabang.
     *
     * @param Request $request Request yang dikirim oleh user.
     * @param string $id ID dari permohonan yang ingin ditampilkan.
     * @return View Mengembalikan view yang sesuai dengan jenis pemohon.
     */
    public function show(Request $request, string $id): View
    {
        $data = PjBarantin::find($id) ?? BarantinCabang::with(['baratininduk:nama_perusahaan,id'])->find($id);
        $register = Register::find($request->register_id);
        $preregister = PreRegister::find($data->pre_register_id);
        if ($preregister->pemohon === 'perorangan') {
            return view('admin.permohonan.show.perorangan', compact('data', 'register'));
        } else {
            if ($preregister->jenis_perusahaan === 'induk' || !$preregister->jenis_perusahaan) {
                return view('admin.permohonan.show.induk', compact('data', 'register'));
            }
            return view('admin.permohonan.show.cabang', compact('data', 'register'));
        }
    }


    /**
     * Menghapus entri permohonan dari database.
     *
     * Fungsi ini bertanggung jawab untuk menghapus entri permohonan berdasarkan ID yang diberikan.
     * Jika penghapusan berhasil, fungsi akan mengembalikan response sukses.
     * Jika penghapusan gagal, fungsi akan mengembalikan response error.
     *
     * @param string $id ID dari permohonan yang akan dihapus.
     * @return JsonResponse yang mengindikasikan hasil operasi.
     */
    public function destroy(string $id): JsonResponse
    {
        $res = Register::destroy($id);
        if ($res) {
            return AjaxResponse::SuccessResponse('Permohonan berhasil dihapus', 'permohonan-datatable');
        }
        return AjaxResponse::ErrorResponse($res, 400);
    }
    /**
     * Menghasilkan JSON response untuk DataTable berdasarkan jenis pemohon.
     *
     * Metode ini mengambil model yang sesuai berdasarkan jenis pemohon, kemudian
     * membangun DataTable yang memungkinkan pencarian berdasarkan tanggal update.
     * Kolom dan aksi spesifik ditambahkan melalui metode `columnDaerahRender`.
     *
     * @param string $pemohon Tipe pemohon yang digunakan untuk memfilter data.
     * @return JsonResponse Response DataTable yang siap digunakan di frontend.
     */
    public function datatable(string $pemohon): JsonResponse
    {
        $model = $this->datatableQueryCheckUser($pemohon);

        $datatable = DataTables::eloquent($model)->addIndexColumn()
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            });
        $action = $pemohon == 'cabang' ? 'admin.permohonan.action.cabang' : 'admin.permohonan.action.induk';
        $barantinKategori = $pemohon == 'cabang' ? 'baratincabang' : 'baratin';
        return $this->columnDaerahRender($datatable, $action, $barantinKategori);

    }

    /**
     * Menambahkan kolom-kolom daerah dan aksi ke DataTable.
     *
     * Metode ini menambahkan kolom UPT, negara, provinsi, dan kota berdasarkan ID yang terkait
     * dari model yang diberikan. Kolom aksi juga ditambahkan berdasarkan parameter yang diterima.
     *
     * @param mixed $datatable DataTable yang sedang dibangun.
     * @param string $action Nama view untuk kolom aksi.
     * @return mixed DataTable yang telah dimodifikasi dengan kolom tambahan.
     */
    private function columnDaerahRender($datatable, string $action, string $barantinKategori)
    {
        return $datatable->addColumn('upt', function ($row) {
            $upt = BarantinApiHelper::getMasterUptByID($row->master_upt_id);
            return $upt['nama_satpel'] . ' - ' . $upt['nama'];
        })
            ->addColumn('negara', function ($row) {
                $negara = BarantinApiHelper::getMasterNegaraByID($row->baratin->negara_id ?? $row->baratincabang->negara_id);
                return $negara['nama'];
            })
            ->filterColumn('negara', function ($query, $keyword) use ($barantinKategori) {
                $negara = collect(BarantinApiHelper::getDataMasterNegara()->original);
                $idNegara = JsonFilterHelper::searchDataByKeyword($negara, $keyword, 'nama')->pluck('id');
                $query->whereHas($barantinKategori, fn($query) => $query->whereIn('negara_id', $idNegara));
            })
            ->addColumn('provinsi', function ($row) {
                $provinsi = BarantinApiHelper::getMasterProvinsiByID($row->baratin->provinsi_id ?? $row->baratincabang->provinsi_id);
                return $provinsi['nama'];
            })
            ->filterColumn('provinsi', function ($query, $keyword) use ($barantinKategori) {
                $provinsi = collect(BarantinApiHelper::getDataMasterProvinsi()->original);
                $idProvinsi = JsonFilterHelper::searchDataByKeyword($provinsi, $keyword, 'nama')->pluck('id');
                $query->whereHas($barantinKategori, fn($query) => $query->whereIn('provinsi_id', $idProvinsi));
            })
            ->addColumn('kota', function ($row) {
                $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($row->baratin->kota ?? $row->baratincabang->kota, $row->baratin->provinsi_id ?? $row->baratincabang->provinsi_id);
                return $kota['nama'];
            })
            ->filterColumn('kota', function ($query, $keyword) use ($barantinKategori) {
                $kota = collect(BarantinApiHelper::getDataMasterKota()->original);
                $idKota = JsonFilterHelper::searchDataByKeyword($kota, $keyword, 'nama')->pluck('id');
                $query->whereHas($barantinKategori, fn($query) => $query->whereIn('kota', $idKota));

            })
            ->addColumn('action', $action)->make(true);
    }

    /**
     * Membuat query untuk DataTable berdasarkan jenis pemohon.
     * Metode ini memfilter data registrasi berdasarkan tipe pemohon ('cabang' atau 'perorangan dan induk')
     * dan membatasi hasil berdasarkan UPT ID pengguna yang terautentikasi jika tersedia.
     *
     * @param string $pemohon Tipe pemohon yang digunakan untuk memfilter query.
     * @return Builder Query builder yang sudah difilter.
     */
    private function datatableQueryCheckUser(string $pemohon)
    {
        $uptId = auth()->guard('admin')->user()->upt_id;

        $model = $pemohon === 'cabang' ? $this->queryRegisterCabang() : $this->queryRegisterPeoranganAndInduk();

        $model = $model->whereHas('preregister', function ($query) use ($pemohon) {
            $query->where('pemohon', $pemohon == 'cabang' || $pemohon == 'perusahaan' ? 'perusahaan' : 'perorangan');
        });

        if ($uptId != $this->uptPusatId) {
            $model = $model->where('master_upt_id', $uptId);
        }

        return $model;
    }
    /**
     * Membuat query untuk mengambil data registrasi cabang.
     * Data yang diambil meliputi informasi cabang dan induk perusahaan,
     * dengan syarat bahwa ID cabang harus ada dan status registrasi tidak disetujui.
     *
     * @return Builder
     */
    public function QueryRegisterCabang(): Builder
    {
        return Register::with([
            'baratincabang.baratininduk:nama_perusahaan,id',
            'baratincabang' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id', 'nitku', 'pj_baratin_id', 'persetujuan_induk');

            }
        ])->select('registers.id', 'master_upt_id', 'barantin_cabang_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('barantin_cabang_id')->whereNot('registers.status', 'DISETUJUI')
            ->whereHas('baratincabang', function ($query) {
                $query->where('persetujuan_induk', 'DISETUJUI');
            });
    }
    /**
     * Membuat query untuk mengambil data registrasi perorangan dan induk.
     * Data yang diambil meliputi informasi perusahaan dan status registrasi,
     * dengan syarat bahwa ID pj_barantin harus ada dan status registrasi tidak disetujui.
     *
     * @return Builder
     */
    private function queryRegisterPeoranganAndInduk(): Builder
    {
        return Register::with([
            'baratin' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id');
            }
        ])->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('pj_barantin_id')->whereNot('registers.status', 'DISETUJUI');
    }
    /**
     * Mengkonfirmasi status pendaftaran berdasarkan ID yang diberikan.
     * Memvalidasi status yang diperlukan dan mengupdate register berdasarkan data yang diberikan.
     * Mengembalikan response JSON yang sesuai berdasarkan hasil operasi.
     *
     * @param string $id ID dari register yang akan dikonfirmasi.
     * @param Request $request Data request yang mengandung status dan keterangan.
     * @return JsonResponse
     */
    public function confirmRegister(string $id, Request $request): JsonResponse
    {
        $request->validate(['status' => 'required|in:DISETUJUI,DITOLAK', 'keterangan' => 'nullable']);
        $register = Register::find($id);
        if ($register) {
            $res = $register->update($request->all());
            if ($res) {
                return AjaxResponse::SuccessResponse('data register ' . $request->status, 'permohonan-datatable');
            }
            return AjaxResponse::ErrorResponse('register gagal di aprove', 400);
        }
        return AjaxResponse::ErrorResponse('data register tidak ditemukan', 400);
    }
    /**
     * Menghasilkan JSON response untuk DataTables yang menampilkan dokumen pendukung.
     *
     * @param string $id ID dari baratin atau barantin cabang.
     * @return JsonResponse
     */
    public function datatablePendukung(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('baratin_id', $id)->orWhere('barantin_cabang_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }
}
