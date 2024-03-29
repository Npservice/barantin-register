let table = $("#user-cabang-datatable").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/barantin/cabang",
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>",
        },
    },
    order: [[2, "asc"]],
    columns: [
        {
            data: "DT_RowIndex",
            searchable: false,
            orderable: false,
        },
        {
            data: "action",
            searchable: false,
            orderable: false,
            width: 60,
        },
        {
            data: "nama_perusahaan",
            name: "nama_perusahaan"
        },
        {
            data: "jenis_identitas",
            name: "jenis_identitas"
        },

        {
            data: "nomor_identitas",
            name: "nomor_identitas"
        },
        {
            data: "nitku",
            name: "nitku"
        },
        {
            data: "telepon",
            name: "telepon"
        },
        {
            data: "fax",
            name: "fax"
        },
        {
            data: "email",
            name: "email"
        },
        {
            data: "negara.nama",
            name: "negara.nama"
        },
        {
            data: "provinsi.nama",
            name: "provinsi.nama"
        },
        {
            data: "kotas.nama",
            name: "kotas.nama"
        },
        {
            data: "alamat",
            name: "alamat"
        },
        {
            data: "status_import",
            name: "status_import",
            orderable: false,
            render: function (data) {
                return renderStatus(data);
            },
        },
    ],
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>",
        },
    },
    drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
});


function BlokirStatus(data) {
    switch (data) {
        case 0:
            return '<h5><span class="badge bg-success">NONAKTIF</span></h5>'
        case 1:
            return '<h5><span class="badge bg-danger">AKTIF</span></h5>'
        default:
            return "Tidak Diketahui";
    }
}


function renderStatus(data) {
    switch (data) {
        case 25:
            return "Importir Umum";
        case 26:
            return "Importir Produsen";
        case 27:
            return "Importir Terdaftar";
        case 28:
            return "Agen Tunggal";
        case 29:
            return "BULOG";
        case 30:
            return "PERTAMINA";
        case 31:
            return "DAHANA";
        case 32:
            return "IPTN";
        default:
            return "Tidak Diketahui";
    }
}

/* table filter handler */
// $('#filter-status-import').select2();
// $('#filter-status-import').change(function () {
//     var val = $(this).val();
//     if (val === 'all') return table.column('baratin.status_import:name').search('').draw();
//     return table.column('baratin.status_import:name').search(val).draw();
// })
// $('#tanggal-register').daterangepicker();
// $('#tanggal-register').on('apply.daterangepicker', function (ev, picker) {
//     var startDate = picker.startDate.format('YYYY-MM-DD');
//     var endDate = picker.endDate.format('YYYY-MM-DD');
//     table.column('updated_at:name').search(startDate + ' - ' + endDate).draw();
// });
// $('#tanggal-register').on('cancel.daterangepicker', function (ev, picker) {
//     table.column('updated_at:name').search('').draw();
// });
