@extends('client.layouts.main')

@section('content') 
<div class="wapper-register register-themed">
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="reg-content card p-4 p-md-5 shadow-lg themed-card">
            <!-- Decorative Lotus Background -->
            <div class="bg-lotus-overlay"></div>

            <div class="logo-head text-center z-index-2 position-relative mb-3">
                <img width="85px" src="{{ asset('images/icon_banhxephap.png') }}" alt="Pháp Luân" class="shadow-sm rounded-circle p-1 bg-white border-gold">
            </div>
            
            <div class="title-section text-center position-relative z-index-2 mb-4">
                <h2 class="fw-bold create-title mb-2">ĐĂNG KÝ QUY Y TAM BẢO</h2>
                <h3 class="fw-bold create-brand mb-1">CHÙA PHƯỚC LỘC</h3>
                <p class="create-address mb-0">(Thôn Trinh Long Khánh, Xã Mỹ Cát, Huyện Phù Mỹ, Bình Định)</p>
                <div class="divider-icon mt-2">❖ ❁ ❖</div>
            </div>

            <form action="{{ route('client.quyy.store') }}" method="POST" class="position-relative z-index-2">
                @csrf
                <div class="row mb-3">
                    <div class="col-md">
                        <label for="full_name" class="form-label">
                            <i class="bi bi-person-fill"></i> Họ và Tên
                        </label>
                        <input type="text" class="form-control input-custom @error('full_name') is-invalid @enderror" 
                            id="full_name" name="full_name" value="{{ old('full_name') }}">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="gender" class="form-label">
                            <i class="bi bi-gender-ambiguous"></i> Giới Tính
                        </label>
                        <select class="form-control input-custom @error('gender') is-invalid @enderror" name="gender">
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="birth_date" class="form-label">
                            <i class="bi bi-calendar-date-fill"></i> Ngày Sinh
                        </label>
                        <input type="date" class="form-control input-custom @error('birth_date') is-invalid @enderror" 
                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">
                            <i class="bi bi-telephone-fill"></i> Số Điện Thoại
                        </label>
                        <input type="text" class="form-control input-custom @error('phone_number') is-invalid @enderror" 
                            id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope-fill"></i> Email
                        </label>
                        <input type="email" class="form-control input-custom @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="province" class="form-label">
                            <i class="bi bi-geo-alt-fill"></i> Tỉnh/Thành Phố
                        </label>
                        <select class="form-control input-custom @error('province') is-invalid @enderror" name="province" id="province">
                            <option value="" {{ old('province') == '' ? 'selected' : '' }} disabled selected hidden"></option>
                        </select>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="district" class="form-label">
                            <i class="bi bi-map-fill"></i> Quận/Huyện
                        </label>
                        <select name="district" id="district" class="form-control input-custom @error('district') is-invalid @enderror">
                            <option value="" {{ old('district') == '' ? 'selected' : '' }} disabled selected hidden"></option>
                        </select>
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="ward" class="form-label">
                            <i class="bi bi-house-door-fill"></i> Phường/Xã
                        </label>
                        <select name="ward" id="ward" class="form-control input-custom @error('ward') is-invalid @enderror">
                            <option value="" {{ old('ward') == '' ? 'selected' : '' }} disabled selected hidden"></option>
                        </select>
                        @error('ward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">
                        <i class="bi bi-geo-fill"></i> Địa Chỉ
                    </label>
                    <input type="text" class="form-control input-custom @error('address') is-invalid @enderror" 
                        id="address" name="address" value="{{ old('address') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">
                        <i class="bi bi-chat-left-text-fill"></i> Ghi Chú
                    </label>
                    <textarea class="form-control input-custom @error('note') is-invalid @enderror" 
                            id="note" name="note" rows="3">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="info-section mb-4 text-center">
                    <p class="mb-1 text-muted" style="font-size: 0.95rem;"><i>Kiểm tra thông tin đã nhập và nhấn nút [Đăng Ký] để gửi thông tin đăng ký Quy Y Tam Bảo</i></p>
                    <p class="mb-0 text-dark"><i class="bi bi-headset text-danger"></i> <b>Hỗ trợ kỹ thuật: <span class="text-danger">{{config('conts.infos.phone_support')}}</span></b></p>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-theme-primary px-5 py-2 fw-bold text-uppercase shadow-sm">
                        <i class="bi bi-send-fill me-1"></i> Gửi Đăng Ký
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.layouts.menu-bar')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/register-quyy.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        .register-themed {
            background-color: #f6efe8;
            background-image: url('https://www.transparenttextures.com/patterns/rice-paper-3.png');
        }
        .themed-card {
            background-color: #fffdf5;
            border: 2px solid #8b0000;
            border-radius: 6px;
            outline: 6px solid #d4af37;
            outline-offset: -10px;
            box-shadow: 0 15px 35px rgba(139, 0, 0, 0.15) !important;
            max-width: 750px;
            width: 100%;
        }
        .bg-lotus-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80%;
            height: 80%;
            transform: translate(-50%, -50%);
            background-image: url("{{asset('/images/lotus-icon.png')}}");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.05;
            z-index: 1;
            pointer-events: none;
        }
        .z-index-2 {
            z-index: 2;
        }
        .border-gold {
            border: 2px solid #d4af37;
        }
        .create-title {
            font-family: 'Playfair Display', serif;
            color: #d32f2f;
            font-size: 28px;
            letter-spacing: 2px;
            text-shadow: 1px 1px 0px rgba(255, 255, 255, 0.8), 2px 2px 3px rgba(0,0,0,0.1);
        }
        .create-brand {
            font-family: 'Lora', serif;
            color: #b8860b;
            font-size: 20px;
            letter-spacing: 1.5px;
        }
        .create-address {
            font-family: 'Lora', serif;
            font-size: 14.5px;
            color: #5d4037;
            font-style: italic;
        }
        .divider-icon {
            color: #d4af37;
            font-size: 18px;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-family: 'Lora', serif;
            font-weight: 600;
            color: #5d4037;
            font-size: 15px;
        }
        .form-label i {
            color: #8b0000;
            margin-right: 4px;
        }
        .input-custom {
            border: 1px solid #d7ccc8;
            background-color: #faf7f2;
            border-radius: 4px;
            padding: 10px 15px;
            color: #3e2723;
            transition: all 0.25s ease;
        }
        .input-custom:focus {
            background-color: #ffffff;
            border-color: #d4af37;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.2);
            outline: none;
        }
        .btn-theme-primary {
            background-color: #8b0000;
            border: 2px solid #6b0000;
            color: #fff;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
            font-size: 16px;
        }
        .btn-theme-primary:hover {
            background-color: #b71c1c;
            border-color: #8b0000;
            color: #fff;
            box-shadow: 0 5px 15px rgba(139, 0, 0, 0.3) !important;
            transform: translateY(-2px);
        }
        .info-section {
            border-top: 1px dashed rgba(212, 175, 55, 0.5);
            padding-top: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/quyy.js') }}"></script>
@endpush

@endsection
