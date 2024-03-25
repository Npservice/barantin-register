@if ($model->baratin->user_id)
    @if ($model->blockir)
        <a class="btn btn-outline-success btn-sm me-2"
            onclick="Open('{{ route('admin.pendaftar.open.akses', $model->id) }}', '{{ $model->baratin->nama_perusahaan }}')"><i
                class="fas fa-user-check"></i></a>
    @endif
    @if (!$model->blockir)
        <a class="btn btn-outline-danger btn-sm me-2"
            onclick="Block('{{ route('admin.pendaftar.block.akses', $model->id) }}', '{{ $model->baratin->nama_perusahaan }}')"><i
                class="fas fa-user-slash"></i></a>
    @endif
@else
    <a class="btn btn-outline-secondary btn-sm me-2" onclick="alertBlockirUser()"><i class="fas fa-user-slash"></i></a>
@endif

<a class="btn btn-outline-info btn-sm"
    onclick="ShowPage('{{ route('admin.pendaftar.show', $model->baratin->id) }}?register_id={{ $model->id }}')"><i
        class="fas fa-eye"></i></a>

@if (!auth()->guard('admin')->user()->upt_id)
    @if (!$model->baratin->user_id)
        <a class="btn btn-outline-warning btn-sm ms-2"
            onclick="CreateUser('{{ route('admin.pendaftar.create.user', $model->id) }}','{{ $model->baratin->nama_perusahaan }}')">
            <i class="fas fa-user-edit"></i></a>
    @endif
    @if ($model->baratin->user_id)
        <a class="btn btn-outline-primary btn-sm ms-2"
            onclick="UserSetting('{{ route('admin.pendaftar.send.user', $model->baratin->user_id) }}','{{ $model->baratin->nama_perusahaan }}')">
            <i class="fas fa-user-cog"></i></a>
    @endif
@endif
