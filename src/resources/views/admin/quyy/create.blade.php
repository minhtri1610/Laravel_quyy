@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <a href="{{ route('admin.quyy.import') }}">
            <button class="btn btn-warning">Nhập hàng loạt</button>
        </a>
    </div>
</div>

<h2 class="page-title-area mt-4">Thêm mới</h2>
<div class="wp-add mt-3">
    <form action="{{ route('admin.quyy.store') }}" method="POST">
        @csrf
        @error('nickname')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror
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
        <div class="form-group">
            <label for="name">Đặt Pháp Danh:</label>
            <input type="text" class="form-control" name="nickname" value="{{ old('nickname') }}" id="m_nick_name" placeholder="Nhập Pháp Danh...">
            <button type="button" class="mt-2 btn-suguest-nickname btn btn-warning" data-route-suggest="{{ route('admin.quyy.gererate') }}"><i class="bi bi-robot"></i> AI Gợi ý</button>
            <div id="loader" class="loader"></div>
            <div class="m-list-nickname"></div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <label for="birth_date" class="form-label">
                    <i class="bi bi-calendar-date-fill"></i> Ngày Sinh
                </label>
                <input type="date" class="form-control input-custom @error('birth_date') is-invalid @enderror"
                    id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="date_registered" class="form-label">
                    <i class="bi bi-calendar-check-fill"></i> Ngày Quy Y
                </label>
                <input type="date" class="form-control input-custom @error('date_registered') is-invalid @enderror"
                    id="date_registered" name="date_registered" value="{{ old('date_registered', date('Y-m-d')) }}">
                @error('date_registered')
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

        <div class="mb-3">
            <p><i>Kiểm tra thông tin đã nhập và nhấn nút [Đăng Ký] để gửi thông tin đăng ký Quy Y Tam Bảo</i></p>
            <p><b>Hỗ trợ kỹ thuật: {{config('conts.infos.phone_support')}}</b></p>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 16px;">
                <i class="bi bi-send-fill"></i> Đăng Ký
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/quyy.js') }}"></script>
    <script src="{{ asset('js/suggest-name.js') }}"></script>
@endpush