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
