$('#pemohon-datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/admin/pemohon',
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>",
        },
    },
    order: [[1, 'asc']],
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
        {
            data: 'preregister.pemohon',
            name: 'preregister.pemohon',
        },
        {
            data: 'preregister.nama',
            name: 'preregister.nama',
        },
        {
            data: 'preregister.email',
            name: 'preregister.email',
        },
        {
            data: 'upt.nama',
            name: 'upt.nama',
        },
        {
            data: 'status',
            name: 'status',
        },
        {
            data: 'keterangan',
            name: 'keterangan',
        },
        {
            data: 'created_at',
            name: 'created_at',
            render: function (data) {
                return moment(data).format('DD-MM-YYYY');
            }
        },

    ],
    drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass(
            "pagination-rounded"
        );
    },
});
