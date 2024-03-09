$("#baratin-datatable").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/admin/baratin",
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
        { data: "baratin.nama_perusahaan", name: "baratin.nama_perusahaan" },
        { data: "baratin.jenis_identitas", name: "baratin.jenis_identitas" },
        { data: "baratin.nomor_identitas", name: "baratin.nomor_identitas" },
        { data: "baratin.telepon", name: "baratin.telepon" },
        { data: "baratin.fax", name: "baratin.fax" },
        { data: "baratin.email", name: "baratin.email" },
        { data: "baratin.negara.nama", name: "baratin.negara.nama" },
        { data: "baratin.provinsi.nama", name: "baratin.provinsi.nama" },
        { data: "baratin.kotas.nama", name: "baratin.kotas.nama" },
        { data: "baratin.alamat", name: "baratin.alamat" },
        {
            data: "baratin.status_import",
            name: "baratin.status_import",
            orderable: false,
            render: function (data) {
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
            },
        },
        {
            data: "created_at",
            name: "created_at",
            render: function (data) {
                return moment(data).format('DD-MM-YYYY')
            },
        },
        { data: "status", name: "status" },
        { data: "keterangan", name: "keterangan" },
    ],
    drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
});
