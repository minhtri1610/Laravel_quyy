@extends('admin.layouts.main-noauth')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="wrapper-login p-4 shadow rounded bg-white">
            <div class="wpl-header text-center mb-3">
                <img class="img-fluid logo" src="{{ asset('images/default/no-image.jpg') }}" alt="">
                <h4 class="fw-bold mt-3">{{ __('lables.pg_login.title')}}</h4>
            </div>
            <div class="wpl-body">
                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="email" class="form-control bdr-50" name="email" id="email" placeholder="{{ __('lables.pg_login.email')}}">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control bdr-50" name="password" id="password" placeholder="{{ __('lables.pg_login.password')}}">
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label for="remember" class="form-check-label">{{ __('lables.pg_login.btn_remember')}}</label>
                        </div>
                        <a href="#" class="text-decoration-none">{{ __('lables.pg_login.btn_forgot')}}</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 20px;">{{ __('lables.pg_login.btn_login')}}</button>
                </form>
                <div class="wpl-footer text-center mt-3">
                    <p>{{ __('lables.pg_login.question')}}<a href="#" class="text-decoration-none">{{ __('lables.pg_login.btn_register')}}</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
