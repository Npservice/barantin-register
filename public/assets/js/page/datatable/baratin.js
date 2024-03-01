$('#masterupt-datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/admin/baratin',
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>",
        },
    },
    order: [[2, 'asc']],
    columns: [
        {
            data: 'DT_RowIndex',
            searchable: false,
            orderable: false
        },
        {
            data: 'action',
            searchable: false,
            orderable: false,
            width: 60
        },
        { data: 'nama_perusahaan', name: 'nama_perusahaan' },
        { data: 'jenis_identitas', name: 'jenis_identitas' },
        { data: 'nomor_identitas', name: 'nomor_identitas' },
        { data: 'telepon', name: 'telepon' },
        { data: 'fax', name: 'fax' },
        { data: 'email', name: 'email' },
        { data: 'negara.nama', name: 'negara.nama' },
        { data: 'provinsi.nama', name: 'provinsi.nama' },
        { data: 'kotas.nama', name: 'kotas.nama' },
        { data: 'alamat', name: 'alamat' },
        { data: 'status_import', name: 'status_import', orderable: false },
        { data: 'status', name: 'status' },

    ],
    drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass(
            "pagination-rounded"
        );
    },
});
