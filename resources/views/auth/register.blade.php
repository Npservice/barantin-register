@extends('layouts.auth.master')
@section('title', 'Register')
@section('text', 'Regiter Baru')
@section('content')
    <div class="p-3">
        <form method="POST" action="{{ route('register') }}" class="form-horizontal mt-3">
            @csrf
            {{-- <div class="d-flex justify-content-start"> --}}
            <div class="row">
                <div class="col-3">
                    <label for="pemohon">Pemohon :</label>
                </div>
                <div class="col-3 me-5">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="pemohon" id="formRadios1" value="perusahaan"
                            checked>
                        <label class="form-check-label" for="formRadios1">
                            Perusahaan
                        </label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pemohon" value="perorangan" id="formRadios2">
                        <label class="form-check-label" for="formRadios2">
                            Perorangan
                        </label>
                    </div>
                </div>
            </div>
            {{-- </div> --}}

            <div class="form-group mb-3 row">
                <label for="nama">Nama</label>
                <div class="col-12">
                    <input id="name" disabled type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                </div>
                @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3 row">
                <label for="email">Email</label>
                <div class="col-12">
                    <input id="email" disabled type="email" class="form-control @error('email') is-invalid @enderror"
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
                    <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Register</button>
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
@push('custom-js')
    <script>
        $('input[name="pemohon"]').change(function() {
            let val = $(this).val();
            let label = $('label[for="nama"]')
            if (val === 'perorangan') {
                label.html('Nama Pemohon');
            } else {
                label.html('Nama Perusahaan');
            }
            $('.form-control').attr('disabled', false);
        });
    </script>
@endpush
