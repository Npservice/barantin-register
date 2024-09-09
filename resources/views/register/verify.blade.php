@extends('layouts.auth.master')
@section('title', 'verify email')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">Verifikasi Email Anda</div>

                    <div class="card-body">
                        @if (isset($message_generate))
                            <div class="alert alert-success w-100 text-center" role="alert">
                                {!! $message_generate !!}
                            </div>
                        @endif

                        Sebelum melanjutkan, harap periksa email Anda untuk tautan verifikasi. Jika anda tidak menerima
                        email tekan tombol dibawah ini ,
                        <form class="d-inline" method="POST" id="form-regenerate">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{ $generate->pre_register_id }}">
                        </form>
                        <button id="button_send" class="btn btn-primary btn-xs disabled w-100 mt-3"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-js')
    <script>
        const _buttons = $('#button_send')
        $(document).ready(function () {
            CountDown()

            _buttons.click(function () {
                $.ajax({
                    data: $('#form-regenerate').serialize(),
                    url: '{{ url('/register/regenerate') }}',
                    dataType: "json",
                    type: "post",
                    success: function (response) {
                        Swal.fire({
                            text: "email verifikasi berhasil dikirim ulang",
                            icon: "success",
                        })
                        CountDown()
                    },
                    error: function (response) {
                        Swal.fire({
                            text: "email verifikasi berhasil gagal dikirim",
                            icon: "danger",
                        })
                    }
                })
            });
        });

        function CountDown() {
            _buttons.addClass('disabled');
            let countdownTime = 5 * 60;

            function updateTimer() {
                const minutes = Math.floor(countdownTime / 60);
                const seconds = countdownTime % 60;
                const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                _buttons.text(formattedTime);
                countdownTime--;
                if (countdownTime < 0) {
                    clearInterval(timerInterval);
                    _buttons.text("Kirim Ulang Kode").removeClass('disabled');
                }
            }

            const timerInterval = setInterval(updateTimer, 1000);
        }
    </script>
@endpush
