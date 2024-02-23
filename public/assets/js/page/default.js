$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function (response) {
            var error = JSON.parse(response.responseText)
            if (error.code === 401) {
                window.location.href = '/'
            }
        }
    });
})

function logout(url) {
    Swal.fire({
        title: "Apa kamu yakin ?",
        text: "Anda tidak akan bisa akses halaman ini lagi!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonText: "KELUAR",
        cancelButtonText: "CANCEL",
        confirmButtonClass: "btn btn-success mt-2",
        cancelButtonClass: "btn btn-danger ms-2 mt-2",
        buttonsStyling: !1,
    }).then(function (t) {
        t.value
            ?
            $.post(url, function () {
                window.location.href = '/';
            })
            : t.dismiss === Swal.DismissReason.cancel
    });

}
function modal(label, size, backdrop, url) {
    $('#modal-dialog').addClass('modal-dialog ' + size);
    $('#modal-label').html(label);
    $('#modal-data').attr('data-bs-backdrop', backdrop);
    $('#spinner').clone().removeClass('d-none').appendTo('#modal-body');
    $('#modal-body').load(url);
    $('#modal-data').modal('show');
}

$('#modal-data').on('hidden.bs.modal', function (e) {
    $('#modal-dialog').removeClass();
    $('#modal-label').html('');
    $('#modal-data').attr('data-bs-backdrop', 'true');
    $('#modal-body').empty()
});

function submit(url, image) {
    if (image) {
        form_data = new FormData();
        let fileInput = $("input[type=file]");
        fileInput.each(function (index, input) {
            var files = input.files;
            if (files.length > 0) {
                form_data.append(input.name, files[0]);
            }
        });
        let form = $("#form-data").serializeArray();
        $.each(form, function (key, value) {
            form_data.append(value.name, value.value);
        });
        if ($("#import-file").length > 0) {
            return FormImport(url, form_data);
        }
        return FormSendImage(url, form_data);
    }
    var data = $("#form-data").serialize();
    FormSend(url, data);
}

function FormSend(url, data) {
    $.ajax({
        data: data,
        url: url,
        dataType: "json",
        type: "post",
        beforeSend: function () {
            $(".form-control").removeClass("is-invalid");
            $(".invalid-feedback").empty();
            $("#button-submit").addClass("disabled").html("Loading...");
        },
        success: function (response) {
            if (response.status) {
                notif("success", response.message);
                TableReload(response.table);
                $("#modal-data").modal("hide");
            } else {
                notif("error", response.message);
                $("#button-submit").removeClass("disabled").html("Simpan");
            }
        },
        error: function (response) {
            $("#button-submit").removeClass("disabled").html("Simpan");
            var respon = response.responseJSON;
            var error = respon.errors;

            if (respon && error) {
                $.each(error, function (key, value) {
                    $("#" + key).addClass("is-invalid");
                    $("#" + key + "-feedback").addClass("d-block").html(value);
                });
                notif('error', 'data tidak valid.');
                return;
            }
            notif("error", "terjadi kesalahan");
        },
    });
}

function notif(type, message) {
    toastr[type](message)
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 1500,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}

function TableReload(table) {
    var table = $("#" + table).DataTable();
    table.ajax.reload();
}
function DeleteAlert(url, type) {
    Swal.fire({
        title: "Apa Anda Yakin?",
        text: "Anda akan kehilangan data " + type,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "DELETE",
                success: function (response) {
                    if (response.status) {
                        notif("success", response.message);
                        TableReload(response.table);
                    } else {
                        notif("error", response.message);
                    }
                },
                error: function (response) {
                    notif("error", "data gagal di hapus");
                },
            });
        }
    });
}
