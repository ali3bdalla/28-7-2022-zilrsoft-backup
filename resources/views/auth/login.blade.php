@extends('layouts.app_login')

@section('content')
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">{{ config('app.name') }}</h3>

            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('management.login') }}">
                    @csrf
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" name="password" required
                               autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="submit" value="تسجيل الدخول" class="btn float-right login_btn btn-block">
                    </div>
                </form>


            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    <a href="{{ route('management.register') }}" style="color: white !important;">انشاء حساب </a>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="#" style="color: #00a65a !important;">نسيت كلمة المرور ؟</a>
                </div>
            </div>
        </div>
    </div>
@endsection