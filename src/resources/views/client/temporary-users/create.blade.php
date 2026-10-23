@extends('client.layouts.main')

@section('title', 'Đăng ký Quy Y Tam Bảo - Chùa Phước Lộc')

@php
    // Ngày sinh hiển thị bằng 3 ô chọn, gộp lại vào input ẩn birth_date (Y-m-d)
    [$birthYear, $birthMonth, $birthDay] = array_pad(explode('-', (string) old('birth_date')), 3, null);
    $currentYear = (int) date('Y');
    $supportPhone = config('conts.infos.phone_support');
@endphp

@section('content')
<div class="register-modern">
    {{-- Đầu trang --}}
    <section class="reg-hero">
        <div class="container text-center">
            <img src="{{ asset('images/logo-sm.png') }}" alt="Logo Chùa Phước Lộc" class="reg-hero-logo" width="84" height="80">
            <p class="reg-eyebrow">Chùa Phước Lộc</p>
            <h1 class="reg-title">Đăng ký Quy Y Tam Bảo</h1>
            <p class="reg-subtitle">Điền thông tin bên dưới, nhà chùa sẽ xem xét và liên hệ xác nhận với quý Phật tử.</p>
            <p class="reg-temple-address"><i class="bi bi-geo-alt"></i> Thôn Trinh Long Khánh, Xã Mỹ Cát, Huyện Phù Mỹ, Bình Định</p>
        </div>
    </section>

    <div class="container reg-container">
        <div class="reg-card">
            <div class="row g-0">
                {{-- Giới thiệu & quy trình --}}
                <aside class="col-lg-4 reg-aside">
                    <img src="{{ asset('images/top-v1.jpg') }}" alt="Phật tử quy y" class="reg-aside-image d-none d-lg-block" loading="lazy">

                    <h2 class="reg-aside-title">Quy trình đăng ký</h2>
                    <ol class="reg-steps">
                        <li>
                            <span class="reg-step-icon"><i class="bi bi-pencil-square"></i></span>
                            <div>
                                <strong>Điền thông tin</strong>
                                <span>Chỉ cần họ tên và số điện thoại</span>
                            </div>
                        </li>
                        <li>
                            <span class="reg-step-icon"><i class="bi bi-person-check"></i></span>
                            <div>
                                <strong>Nhà chùa xét duyệt</strong>
                                <span>Liên hệ xác nhận qua điện thoại</span>
                            </div>
                        </li>
                        <li>
                            <span class="reg-step-icon"><i class="bi bi-flower1"></i></span>
                            <div>
                                <strong>Nhận Pháp danh</strong>
                                <span>Kèm mã QR để tra cứu thông tin</span>
                            </div>
                        </li>
                    </ol>
                </aside>

                {{-- Form --}}
                <div class="col-lg-8">
                    <form action="{{ route('client.quyy.store') }}" method="POST" id="register-form" class="reg-form">
                        @csrf

                        @if ($errors->any())
                            <div class="reg-alert" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <div>
                                    <strong>Vui lòng kiểm tra lại thông tin</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- 1. Thông tin cá nhân --}}
                        <fieldset class="reg-section">
                            <legend class="reg-section-head">
                                <span class="reg-section-num">1</span>
                                <span>
                                    <span class="reg-section-title">Thông tin cá nhân</span>
                                    <span class="reg-section-desc">Các mục có dấu <span class="req">*</span> là bắt buộc</span>
                                </span>
                            </legend>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên <span class="req">*</span></label>
                                <input type="text" class="form-control input-custom @error('full_name') is-invalid @enderror"
                                    id="full_name" name="full_name" value="{{ old('full_name') }}"
                                    placeholder="VD: Nguyễn Văn An" autocomplete="name" autocapitalize="words"
                                    maxlength="255" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-5">
                                    <span class="form-label d-block" id="gender-label">Giới tính</span>
                                    <div class="gender-group @error('gender') is-invalid @enderror" role="radiogroup" aria-labelledby="gender-label">
                                        @foreach (['male' => ['Nam', 'bi-gender-male'], 'female' => ['Nữ', 'bi-gender-female'], 'other' => ['Khác', 'bi-gender-ambiguous']] as $value => [$text, $icon])
                                            <input type="radio" class="btn-check" name="gender" id="gender_{{ $value }}" value="{{ $value }}"
                                                autocomplete="off" {{ old('gender') === $value ? 'checked' : '' }}>
                                            <label class="btn btn-gender" for="gender_{{ $value }}"><i class="bi {{ $icon }}"></i> {{ $text }}</label>
                                        @endforeach
                                    </div>
                                    @error('gender')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-7">
                                    <span class="form-label d-block" id="birth-label">Ngày sinh</span>
                                    <div class="birth-group" role="group" aria-labelledby="birth-label">
                                        <select id="birth_day" class="form-select input-custom @error('birth_date') is-invalid @enderror" aria-label="Ngày">
                                            <option value="">Ngày</option>
                                            @for ($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}" {{ (int) $birthDay === $d ? 'selected' : '' }}>{{ $d }}</option>
                                            @endfor
                                        </select>
                                        <select id="birth_month" class="form-select input-custom @error('birth_date') is-invalid @enderror" aria-label="Tháng">
                                            <option value="">Tháng</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ (int) $birthMonth === $m ? 'selected' : '' }}>Th {{ $m }}</option>
                                            @endfor
                                        </select>
                                        <select id="birth_year" class="form-select input-custom @error('birth_date') is-invalid @enderror" aria-label="Năm">
                                            <option value="">Năm</option>
                                            @for ($y = $currentYear; $y >= $currentYear - 100; $y--)
                                                <option value="{{ $y }}" {{ (int) $birthYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <input type="hidden" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- 2. Liên hệ --}}
                        <fieldset class="reg-section">
                            <legend class="reg-section-head">
                                <span class="reg-section-num">2</span>
                                <span>
                                    <span class="reg-section-title">Thông tin liên hệ</span>
                                    <span class="reg-section-desc">Nhà chùa sẽ gọi theo số này để xác nhận</span>
                                </span>
                            </legend>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Số điện thoại <span class="req">*</span></label>
                                    <input type="tel" class="form-control input-custom @error('phone_number') is-invalid @enderror"
                                        id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                        placeholder="VD: 0912 345 678" inputmode="tel" autocomplete="tel"
                                        maxlength="15" pattern="[0-9+\s.\-]{9,15}" title="Số điện thoại gồm 9-15 chữ số"
                                        required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="optional">không bắt buộc</span></label>
                                    <input type="email" class="form-control input-custom @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="VD: ten@gmail.com" inputmode="email" autocomplete="email" maxlength="255">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- 3. Địa chỉ --}}
                        <fieldset class="reg-section">
                            <legend class="reg-section-head">
                                <span class="reg-section-num">3</span>
                                <span>
                                    <span class="reg-section-title">Địa chỉ</span>
                                    <span class="reg-section-desc">Nơi quý Phật tử đang sinh sống</span>
                                </span>
                            </legend>

                            @include('commons._address-fields', [
                                'province' => old('province'),
                                'district' => old('district'),
                                'ward' => old('ward'),
                                'useOldAddress' => old('address_type') === 'old',
                            ])

                            <div>
                                <label for="address" class="form-label">Thôn/Xóm, số nhà</label>
                                <input type="text" class="form-control input-custom @error('address') is-invalid @enderror"
                                    id="address" name="address" value="{{ old('address') }}"
                                    placeholder="Thôn/Xóm/Ấp, số nhà, tên đường" autocomplete="street-address" maxlength="255">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset>

                        {{-- 4. Ghi chú --}}
                        <fieldset class="reg-section">
                            <legend class="reg-section-head">
                                <span class="reg-section-num">4</span>
                                <span>
                                    <span class="reg-section-title">Ghi chú <span class="optional">không bắt buộc</span></span>
                                    <span class="reg-section-desc">Điều quý Phật tử muốn gửi đến nhà chùa</span>
                                </span>
                            </legend>

                            <label for="note" class="visually-hidden">Ghi chú</label>
                            <textarea class="form-control input-custom @error('note') is-invalid @enderror"
                                id="note" name="note" rows="3" placeholder="Nhập nội dung (nếu có)">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <div class="reg-submit">
                            <button type="submit" class="btn btn-submit" id="btn-submit">
                                <span class="btn-label">Gửi đăng ký <i class="bi bi-arrow-right"></i></span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span> Đang gửi...
                                </span>
                            </button>
                            <p class="reg-privacy">
                                <i class="bi bi-shield-check"></i> Thông tin chỉ dùng cho việc quản lý Phật tử của nhà chùa.
                            </p>
                            <p class="reg-support">
                                Cần hỗ trợ? Gọi
                                <a href="tel:{{ preg_replace('/\D/', '', $supportPhone) }}"><i class="bi bi-telephone-fill"></i> {{ $supportPhone }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('client.layouts.menu-bar')

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register-form.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/quyy.js') }}"></script>
    <script src="{{ asset('js/register-form.js') }}"></script>
@endpush

@endsection
