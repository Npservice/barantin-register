@extends('layouts.auth.master')
@section('title', 'verify email')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (isset($message_generate))
                            <div class="alert alert-success w-100 text-center" role="alert">
                                {!! $message_generate !!}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ url('/register/regenerate') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $generate->token }}">
                            <input type="hidden" name="user_id" value="{{ $generate->pre_register_id }}">
                        </form>
                        <button id="timer" class="btn btn-primary btn-xs disabled w-100 mt-3"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-js')
    <script>
        $(document).ready(function() {
            // Set the countdown time (5 minutes in seconds)
            let countdownTime = 5 * 60;

            // Function to update the countdown timer
            function updateTimer() {
                const minutes = Math.floor(countdownTime / 60);
                const seconds = countdownTime % 60;

                // Format time to always show two digits
                const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                // Display the timer using jQuery
                $('#timer').text(formattedTime);

                // Decrease the countdown time by 1 second
                countdownTime--;

                // Stop the countdown when time is up
                if (countdownTime < 0) {
                    clearInterval(timerInterval);
                    $('#timer').text("Kirim Ulang Kode").removeClass('disabled');
                }
            }

            // Update the timer every second
            const timerInterval = setInterval(updateTimer, 1000);
        });

    </script>

@endpush
