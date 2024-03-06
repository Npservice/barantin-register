<a class="btn btn-outline-warning btn-sm me-2" onclick="" title="Edit"><i class="fas fa-edit"></i></a>
<a class="btn btn-outline-success btn-sm me-2"
    onclick="ConfirmRegister('{{ route('admin.baratin.confirm.register', $model->id) }}', '{{ $model->baratin->nama_perusahaan }}')"><i
        class="fas fa-check"></i></a>

<a class="btn btn-outline-info btn-sm" onclick="ShowPage('{{ route('admin.baratin.show', $model->baratin->id) }}')"><i
        class="fas fa-eye"></i></a>
