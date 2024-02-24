@extends('layouts.auth.master')
@section('title', 'Register')
@section('text', 'Regiter Baru')
@section('content')
    <div class="p-3">
        <form method="GET" action="" class="form-horizontal mt-3">
            @csrf

            <div class="form-group mb-3 row">
                <label for="nama">Username</label>
                <div class="col-12">
                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                        name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                </div>
                @error('username')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3 row">
                <label for="email">Email</label>
                <div class="col-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email"
                        placeholder="example@email.com">
                </div>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group text-center row mt-2 pt-1">
                <div class="col-12">
                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Register</button>
                </div>
            </div>

            <div class="form-group mt-2 mb-0 row">
                <div class="col-12 mt-3 text-center">
                    <a href="{{ route('login') }}" class="text-muted">Back to login</a>
                </div>
            </div>
        </form>
        <!-- end form -->
    </div>


@endsection
