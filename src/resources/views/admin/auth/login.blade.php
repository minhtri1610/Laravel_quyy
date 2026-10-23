@extends('admin.layouts.main-noauth')

@section('title', __('lables.pg_login.page_title'))

@section('content')
<div class="pl-theme auth-page">
    {{-- Hình minh họa (chỉ hiện trên màn hình lớn) --}}
    <section class="auth-visual d-none d-lg-flex">
        <div class="auth-visual-content">
            <span class="pl-eyebrow auth-visual-eyebrow">{{ __('lables.pg_login.temple') }}</span>
            <h2 class="auth-visual-title">{{ __('lables.pg_login.system_name') }}</h2>
            <p class="auth-visual-desc">{{ __('lables.pg_login.system_desc') }}</p>
        </div>
    </section>

    {{-- Form đăng nhập --}}
    <section class="auth-panel">
        <div class="auth-card">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-sm.png') }}" alt="Logo Chùa Phước Lộc" class="pl-logo auth-logo" width="76" height="72">
                <p class="pl-eyebrow mb-2">{{ __('lables.pg_login.eyebrow') }}</p>
                <h1 class="auth-title">{{ __('lables.pg_login.title') }}</h1>
                <p class="auth-subtitle">{{ __('lables.pg_login.subtitle') }}</p>
            </div>

            @if ($errors->any())
                <div class="pl-alert" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" id="login-form">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('lables.pg_login.email') }}</label>
                    <div class="auth-input">
                        <i class="bi bi-envelope auth-input-icon" aria-hidden="true"></i>
                        <input type="email" class="form-control input-custom @error('email') is-invalid @enderror"
                            name="email" id="email" value="{{ old('email') }}"
                            placeholder="email@example.com" autocomplete="username" inputmode="email"
                            required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('lables.pg_login.password') }}</label>
                    <div class="auth-input">
                        <i class="bi bi-lock auth-input-icon" aria-hidden="true"></i>
                        <input type="password" class="form-control input-custom auth-password @error('password') is-invalid @enderror"
                            name="password" id="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="auth-toggle-password" id="toggle-password"
                            aria-label="{{ __('lables.pg_login.show_password') }}"
                            data-label-show="{{ __('lables.pg_login.show_password') }}"
                            data-label-hide="{{ __('lables.pg_login.hide_password') }}">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="auth-capslock d-none" id="capslock-warning">
                        <i class="bi bi-capslock-fill"></i> {{ __('lables.pg_login.capslock') }}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="form-check-label">{{ __('lables.pg_login.btn_remember') }}</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit" id="btn-login">
                    <span class="btn-label">{{ __('lables.pg_login.btn_login') }} <i class="bi bi-arrow-right"></i></span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span> {{ __('lables.pg_login.logging_in') }}
                    </span>
                </button>
            </form>

            <p class="auth-hint">
                <i class="bi bi-info-circle"></i> {{ __('lables.pg_login.forgot_hint') }}
            </p>
        </div>

        <div class="auth-footer">
            <a href="{{ route('client.index') }}" class="auth-back"><i class="bi bi-arrow-left"></i> {{ __('lables.pg_login.back_home') }}</a>
            <span>&copy; 2025 HTQL chùa Phước Lộc</span>
        </div>
    </section>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin-login.js') }}"></script>
@endpush
