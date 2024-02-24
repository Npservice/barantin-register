@extends('layouts.auth.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('message_token'))
                    <div class="alert alert-danger w-100 text-center" role="alert">
                        {!! $message !!}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
