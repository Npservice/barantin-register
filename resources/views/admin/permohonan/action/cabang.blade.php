{{-- <a class="btn btn-outline-danger btn-sm"
    onclick="DeleteAlert('{{ route('admin.permohonan.destroy', $model->id) }}','{{ $model->nama }}')"><i
        class="fas fa-trash"></i></a> --}}

@if (auth()->guard()->user()->upt_id)
    @if (!$model->status || $model->status === 'MENUNGGU')
        <a class="btn btn-outline-success btn-sm xe-2"
            onclick="ConfirmRegister('{{ route('admin.permohonan.confirm.register', $model->id) }}', '{{ $model->baratincabang->nama_perusahaan }}')"><i
                class="fas fa-check"></i></a>
    @else
        <button class="btn btn-outline-danger btn-sm" disabled><i class="fas fa-exclamation-triangle"></i></button>
    @endif
@endif
<a class="btn btn-outline-info btn-sm"
    onclick="ShowPage('{{ route('admin.permohonan.show', $model->baratincabang->id) }}?register_id={{ $model->id }}')"><i
        class="fas fa-eye"></i></a>
