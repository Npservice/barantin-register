$('#masterupt-datatable').DataTable({
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
            data: 'pemohon',
            name: 'pemohon',
        },
        {
            data: 'nama',
            name: 'nama',
        },
        {
            data: 'email',
            name: 'email',
        },
        {
            data: 'created_at',
            name: 'created_at',
            render: function (data) {
                return moment(data).format('DD - MM - YYYY');
            }
        },
        {
            data: 'status',
            name: 'status',
        },
        {
            data: 'action',
            searchable: false,
            orderable: false,
            width: 60
        },

    ],
    drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass(
            "pagination-rounded"
        );
    },
});
