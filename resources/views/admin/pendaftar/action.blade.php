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

<a class="btn btn-outline-info btn-sm"
    onclick="ShowPage('{{ route('admin.pendaftar.show', $model->baratin->id) }}?register_id={{ $model->id }}')"><i
        class="fas fa-eye"></i></a>
