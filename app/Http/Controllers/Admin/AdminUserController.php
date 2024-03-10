<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Helpers\AjaxResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AdminUserRequestStore;
use App\Http\Requests\AdminUserRequestUpdate;


class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
        $this->middleware(function ($request, $next) {
            if (auth()->guard('admin')->user()->upt_id || auth()->guard('web')->user()) {
                abort(403);
            }
            return $next($request);
        });
    }
    public function index(): JsonResponse|View
    {
        if (request()->ajax()) {
            return $this->datatable();
        }
        return view('admin.admin-user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.admin-user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminUserRequestStore $request): JsonResponse
    {
        $data = collect($request->except(['upt', 'token_']))->merge(['upt_id' => $request->upt]);
        $res = Admin::create($data->all());
        if ($res) {
            return AjaxResponse::SuccessResponse('user admin berhasil ditambah', 'admin-user-datatable');
        }
        return AjaxResponse::ErrorResponse('user admin gagal ditambah', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data = Admin::find($id);
        return view('admin.admin-user.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUserRequestUpdate $request, string $id): JsonResponse
    {
        if (!$request->password) {
            $data = collect($request->except(['upt', 'password']))->merge(['upt_id' => $request->upt]);
        } else {
            $data = collect($request->except(['upt']))->merge(['upt_id' => $request->upt]);

        }
        $res = Admin::find($id)->update($data->all());
        if ($res) {
            return AjaxResponse::SuccessResponse('admin user berhasil diupdate', 'admin-user-datatable');
        }
        return AjaxResponse::ErrorResponse('admin user gagal diupdate', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $res = Admin::destroy($id);
        if ($res) {
            return AjaxResponse::SuccessResponse('admin user berhasil dihapus', 'admin-user-datatable');
        }
        return AjaxResponse::ErrorResponse('admin user  gagal dihapus', 400);
    }
    static function datatable(): JsonResponse
    {
        $model = Admin::with('upt:nama,id')->select('admins.id', 'admins.nama', 'email', 'username', 'upt_id')->whereNotNull('upt_id');

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'admin.admin-user.action')
            ->toJson();
    }
}
