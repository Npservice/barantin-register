
function UptSelect() {
    $('.upt-select').select2({
        placeholder: 'select item',
        ajax: {
            type: 'GET',
            url: '/select/upt',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (obj) {
                        return { id: obj.id, text: obj.nama }
                    })
                }
            }
        }
    })
}
function ProvinsiSelect(provinsi_id) {
    $('.provinsi-select').select2({
        placeholder: 'select item',
        minimumResultsForSearch: -1,
        ajax: {
            type: 'GET',
            url: '/select/provinsi',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (obj) {
                        return { id: obj.id, text: obj.nama }
                    })
                }
            }
        }
    })
    if (provinsi_id) {
        $.ajax({
            type: 'GET',
            url: '/select/provinsi/',
            data: {
                provinsi_id: provinsi_id
            }
        }).then(function (response) {
            var option = new Option(response.nama, response.id, true, true);
            $('.provinsi-select').append(option).trigger('change');

            $('.provinsi-select').trigger({
                type: 'select2:select',
                params: {
                    results: response
                }
            });
        });
    }
}
function NegaraSelect(negara_id) {
    $('.negara-select').select2({
        placeholder: 'select item',
        minimumResultsForSearch: -1,
        ajax: {
            type: 'GET',
            url: '/select/negara',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (obj) {
                        return { id: obj.id, text: obj.kode + ' - ' + obj.nama }
                    })
                }
            }
        }
    })


    if (negara_id) {
        $.ajax({
            type: 'GET',
            url: '/select/negara/',
            data: {
                negara_id: negara_id
            }
        }).then(function (response) {
            var option = new Option(response.kode + ' - ' + response.nama, response.id, true, true);
            $('.negara-select').append(option).trigger('change');

            $('.negara-select').trigger({
                type: 'select2:select',
                params: {
                    results: response
                }
            });
        });
    }

}
function KotaSelect(kota_id) {
    $('.kota-select').select2({
        placeholder: "select item",
        minimumResultsForSearch: -1,
        //
    });
    $('.provinsi-select').change(function () {
        $('.kota-select').empty()
        var provinsi = $(this).val();
        $('.kota-select').select2({
            placeholder: "Select Item",
            minimumResultsForSearch: -1,
            //
            ajax: {
                url: '/select/kota/' + provinsi,
                type: "GET",
                dataType: "json",
                data: function (params) {
                    return {
                        q: params.term
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.id, text: obj.nama }
                        })
                    }
                }

            }
        })
        if (kota_id) {
            $.ajax({
                type: 'GET',
                url: '/select/kota/' + provinsi,
                data: {
                    kota_id: kota_id
                }
            }).then(function (response) {
                var option = new Option(response.nama, response.id, true, true);
                $('.kota-select').append(option).trigger('change');

                $('.kota-select').trigger({
                    type: 'select2:select',
                    params: {
                        results: response
                    }
                });
            });
        }
    })
}
$('#negara').on('change', function () {
    let val = $(this).val();
    console.log(val);
    // if (val === 99) {
    //     $('.kota-select').addClass('d-none');
    //     $('.provinsi-select').addClass('d-none');
    // } else {
    //     $('.kota-select').empty().addClass('d-none');
    //     $('.provinsi-select').empty().addClass('d-none');
    //     // $('.kota-select').empty();
    //     // $('.provinsi-select').empty();
    // }
});
