$('#masterupt-datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/admin/master-upt',
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
            data: 'kode_satpel',
            name: 'kode_satpel',
        },
        {
            data: 'kode_upt',
            name: 'kode_upt',
        },
        {
            data: 'nama',
            name: 'nama',
        },
        {
            data: 'nama_en',
            name: 'nama_en',
        },
        {
            data: 'wilayah_kerja',
            name: 'wilayah_kerja',
        },
        {
            data: 'nama_satpel',
            name: 'nama_satpel',
        },
        {
            data: 'kota',
            name: 'kota',
        },
        {
            data: 'kode_pelabuhan',
            name: 'kode_pelabuhan',
        },
        {
            data: 'tembusan',
            name: 'tembusan',
        },
        {
            data: 'otoritas_pelabuhan',
            name: 'otoritas_pelabuhan',
        },
        {
            data: 'syah_bandar_pelabuhan',
            name: 'syah_bandar_pelabuhan',
        },
        {
            data: 'kepala_kantor_bea_cukai',
            name: 'kepala_kantor_bea_cukai',
        },
        {
            data: 'nama_pengelola',
            name: 'nama_pengelola',
        },
        {
            data: 'stat_ppkol',
            name: 'stat_ppkol',
        },
        {
            data: 'stat_insw',
            name: 'stat_insw',
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
