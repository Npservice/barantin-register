$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
})

function logout(url) {
    $.post(url, function () {
        window.location.href = '/';
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
            // if (response.status) {
            //     AlertInfo("primary", "fa fa-check", response.message);
            //     TableReload(response.table);
            //     $("#modal-data").modal("hide");
            // } else {
            //     AlertInfo("danger", "fa fa-warning", response.message);
            //     $("#button-submit").removeClass("disabled").html("Simpan");
            // }
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
                // AlertInfo("danger", "fa fa-warning", "data tidak valid");
                return;
            }
            // AlertInfo("danger", "fa fa-warning", "terjadi kesalahan");
        },
    });
}
