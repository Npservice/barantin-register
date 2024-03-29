table = $("#permohonan-datatable").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/admin/permohonan/datatable/data/cabang",
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
            data: "status", name: "status"
        },
        {
            data: "blockir", name: "blockir", render: function (data) { return BlokirStatus(data) }
        },
        {
            data: "upt.nama", name: "upt.nama"
        },
        {
            data: "baratincabang.baratininduk.nama_perusahaan", name: "baratincabang.baratininduk.nama_perusahaan"
        },
        {
            data: "baratincabang.nama_perusahaan", name: "baratincabang.nama_perusahaan"
        },
        {
            data: "baratincabang.jenis_identitas", name: "baratincabang.jenis_identitas"
        },
        {
            data: "baratincabang.nomor_identitas", name: "baratincabang.nomor_identitas"
        },
        {
            data: "baratincabang.nitku", name: "baratincabang.nitku"
        },
        {
            data: "baratincabang.telepon", name: "baratincabang.telepon"
        },
        {
            data: "baratincabang.fax", name: "baratincabang.fax"
        },
        {
            data: "baratincabang.email", name: "baratincabang.email"
        },
        {
            data: "baratincabang.negara.nama", name: "baratincabang.negara.nama"
        },
        {
            data: "baratincabang.provinsi.nama", name: "baratincabang.provinsi.nama"
        },
        {
            data: "baratincabang.kotas.nama", name: "baratincabang.kotas.nama"
        },
        {
            data: "baratincabang.alamat", name: "baratincabang.alamat"
        },
        {
            data: "baratincabang.status_import",
            name: "baratincabang.status_import",
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
            data: "updated_at",
            name: "updated_at",
            render: function (data) {
                return moment(data).format('DD-MM-YYYY')
            },
        },

        { data: "keterangan", name: "keterangan" },
    ],
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
    }
}
/* table filter handler */
$('#filter-status-import').select2();
$('#filter-status-import').change(function () {
    var val = $(this).val();
    if (val === 'all') return table.column('baratincabang.status_import:name').search('').draw();
    return table.column('baratincabang.status_import:name').search(val).draw();
})
$('#tanggal-register').daterangepicker();
$('#tanggal-register').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');
    table.column('updated_at:name').search(startDate + ' - ' + endDate).draw();
});
$('#tanggal-register').on('cancel.daterangepicker', function (ev, picker) {
    table.column('updated_at:name').search('').draw();
});
