@if ($model->baratinCabang->user_id)
    <a class="btn btn-sm me-2 {{ $model->blockir ? 'btn-outline-success' : 'btn-outline-danger' }}"
        onclick="{{ $model->blockir ? 'Open' : 'Block' }}('{{ route('admin.pendaftar.' . ($model->blockir ? 'open' : 'block') . '.akses', $model->id) }}', '{{ $model->baratinCabang->nama_perusahaan }}')">
        <i class="fas {{ $model->blockir ? 'fa-user-check' : 'fa-user-slash' }}"></i>
    </a>
@else
    <a class="btn btn-outline-secondary btn-sm me-2" onclick="alertBlockirUser()"><i class="fas fa-user-slash"></i></a>
@endif

<a class="btn btn-outline-info btn-sm"
    onclick="ShowPage('{{ route('admin.pendaftar.show', $model->baratinCabang->id) }}?register_id={{ $model->id }}')"><i
        class="fas fa-eye"></i></a>

@if (auth()->guard('admin')->user()->upt_id == $uptPusatId)
    <a class="btn btn-sm ms-2 {{ $model->baratinCabang->user_id ? 'btn-outline-primary' : 'btn-outline-warning' }}"
        onclick="{{ $model->baratinCabang->user_id ? 'UserSetting' : 'CreateUser' }}('{{ route('admin.pendaftar.' . ($model->baratinCabang->user_id ? 'send.user' : 'create.user'), $model->baratinCabang->user_id ? $model->baratinCabang->user_id : $model->id) }}', '{{ $model->baratinCabang->nama_perusahaan }}')">
        <i class="fas {{ $model->baratinCabang->user_id ? 'fa-user-cog' : 'fa-user-edit' }}"></i>
    </a>
@endif
