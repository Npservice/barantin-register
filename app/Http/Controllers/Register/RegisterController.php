<?php

namespace App\Http\Controllers\Register;

use App\Models\PreRegister;
use App\Models\PjBaratanKpp;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\DokumenPendukungRequestStore;

class RegisterController extends Controller
{

    /* register  formulir handler */
    public function RegisterFormulirIndex(string $id): View|RedirectResponse
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $baratan = PjBaratanKpp::where('email', $register->email)->first();
        if ($baratan) {
            return view('register.form.index', compact('id', 'baratan'));
        }
        return view('register.form.index', compact('id'));
    }
    /* register form request by ajax */
    public function RegisterForm(string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        return view('register.form.partial.perorangan', compact('register'));
    }
    /* status register */
    public function StatusRegister(): View
    {
        return view('register.form.partial.status-register');
    }
    /* failed register */
    public function RegisterFailed(): View
    {
        $message = session('message_token');
        return view('register.failed', compact('message'));
    }
    static function CheckRegister(PreRegister $register): RedirectResponse|bool
    {
        if (!$register || !$register->verify_email) {
            return redirect()->route('register.failed')->with(['message_token' => 'Email Not Verified. Please register again.']);
        }
        if ($register->status === 'MENUNGGU') {
            return redirect()->route('register.failed')->with(['message_token' => 'Data in process']);
        }
        if ($register->status === 'TOLAK') {
            return redirect()->route('register.failed')->with(['message_token' => 'Data in process']);
        }
        return true;
    }




    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function datatable()
    {

    }

    /* register dokumen pendukung saved */
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

    /* dokumen pendukung datatable hanlder */
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
    public function DokumenPendukungDestroy(string $id): JsonResponse
    {

        $data = DokumenPendukung::find($id);
        $file = Storage::disk('public')->delete($data->file);
        $res = $data->delete();

        if ($res) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil dihapus', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal dihapus', 200);
    }

}
