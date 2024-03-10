$('#admin-user-datatable').dataTable({
    processing: true,
    serverSide: true,
    ajax: '/admin/admin-user',
    order: [
        ['1', 'desc']
    ],
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
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
        data: 'username',
        name: 'username',
    },
    {
        data: 'upt.nama',
        name: 'upt.nama',
    },

    {
        data: 'action',
        orderable: false,
        searchable: false
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
