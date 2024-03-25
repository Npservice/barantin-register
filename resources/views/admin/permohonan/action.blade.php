{{-- <a class="btn btn-outline-danger btn-sm"
    onclick="DeleteAlert('{{ route('admin.permohonan.destroy', $model->id) }}','{{ $model->nama }}')"><i
        class="fas fa-trash"></i></a> --}}

@if (auth()->guard()->user()->upt_id)
    <a class="btn btn-outline-success btn-sm xe-2"
        onclick="ConfirmRegister('{{ route('admin.permohonan.confirm.register', $model->id) }}', '{{ $model->baratin->nama_perusahaan }}')"><i
            class="fas fa-check"></i></a>
@endif

<a class="btn btn-outline-info btn-sm"
    onclick="ShowPage('{{ route('admin.permohonan.show', $model->baratin->id) }}?register_id={{ $model->id }}')"><i
        class="fas fa-eye"></i></a>
