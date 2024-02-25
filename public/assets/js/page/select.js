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
function ProvinsiSelect() {
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
}
function KotaSelect() {
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
    })
}
