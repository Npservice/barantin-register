$('#status-datatable').dataTable({
    processing: true,
    serverSide: true,
    ajax: '/register/status',
    order: [
        ['7', 'desc']
    ],
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'baratin.nama_perusahaan',
        name: 'baratin.nama_perusahaan',

    },
    {
        data: 'baratin.nama_tdd',
        name: 'baratin.nama_tdd',
    },
    {
        data: 'baratin.jabatan_tdd',
        name: 'baratin.jabatan_tdd',
    },
    {
        data: 'kota',
        name: 'kota',
    },
    {
        data: 'upt',
        name: 'upt'
    },
    {
        data: 'updated_at',
        name: 'updated_at',
        render: function (data) {
            return moment(data).format('DD-MM-YYYY')
        }
    },
    {
        data: 'status',
        name: 'status'
    },
    {
        data: 'keterangan',
        name: 'keterangan',
    }
    ],
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>",
        },
    },
    drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass(
            "pagination-rounded"
        );
    },
});
