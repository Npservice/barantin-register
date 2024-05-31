    <a class="btn btn-outline-primary btn-sm" onclick="ShowPage('{{ route('barantin.cabang.show', $model->id) }}')"><i class="fas fa-eye"></i></a>
    @if ($model->persetujuan_induk == 'MENUNGGU')
        <a class="btn btn-outline-success btn-sm xe-2" onclick="ConfirmCabang('{{ route('barantin.cabang.confirmasi', $model->id) }}', '{{ $model->nama_perusahaan }}')"><i class="fas fa-check"></i></a>
    @endif
