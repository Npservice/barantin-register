@extends('layouts.auth.master')
@section('title', 'Login')
@section('text', 'Sign in')
@section('content')
    <div class="p-3">
        <form class="form-horizontal mt-3" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group mb-3 row">
                <label for="email">Username</label>
                <div class="col-12">
                    <input id="email" type="text" class="form-control @error('username') is-invalid @enderror"
                        name="username" value="{{ old('username') }}" required autocomplete="username" autofocus
                        placeholder="username">
                </div>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3 row">
                <label for="password">Password</label>
                <div class="col-12">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="password">
                </div>
            </div>

            <div class="form-group mb-3 row">
                <div class="col-12">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="form-label ms-1" for="customCheck1">Remember me</label>
                    </div>
                </div>
            </div>

            <div class="form-group mb-1 text-center row mt-3 pt-1">
                <div class="col-12">
                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log
                        In</button>
                </div>
            </div>
            <div class="form-group mb-3 text-center row  pt-1">
                <div class="col-12">
                    <a href="{{ route('register.status') }}" class="w-100 btn btn-info waves-effect waves-light"
                        target="_blank">Cek Status Register</a>
                </div>
            </div>

            <div class="form-group mb-0 row mt-2">
                <div class="col-sm-7 mt-3">
                    <a href="{{ route('register.create') }}" class="text-muted"><i class="mdi mdi-lock"></i>Register
                        ulang</a>
                </div>
                <div class="col-sm-5 mt-3">
                    <a href="{{ route('register.index') }}" class="text-muted"><i
                            class="mdi mdi-account-circle"></i>Register
                        baru</a>
                </div>
            </div>
        </form>
    </div>
@endsection
