$('#thumbnile').dropify()
$('input[name="kuasa"]').change(function () {
    let val = $(this).val();
    if (val === 'ya') {
        $('#form-kuasa').removeClass('d-none');
        return
    }
    $('#form-kuasa').addClass('d-none');

});
$('#datatable-dokumen-pendukung').DataTable({
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
$('#datatable-kuasa').DataTable({
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
$('#datatable').DataTable({
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
