@extends('admin.layouts.main')
@section('title', 'Chỉnh sửa')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiết</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.quyy.update', ['uid' => $data->uid]) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md">
                            <label for="full_name" class="form-label">
                                <i class="bi bi-person-fill"></i> Họ và Tên
                            </label>
                            <input type="text" class="form-control input-custom @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" value="{{ $data->name ?? old('full_name') }}">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md">
                            <label for="nickname" class="form-label">
                                <i class="bi bi-person-fill"></i> Pháp Danh
                            </label>
                            <input type="text" class="form-control input-custom @error('nickname') is-invalid @enderror"
                                id="nickname" name="nickname" value="{{ $data->nickname ?? old('nickname') }}">
                            @error('nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="gender" class="form-label">
                                <i class="bi bi-gender-ambiguous"></i> Giới Tính
                            </label>
                            <select class="form-control input-custom @error('gender') is-invalid @enderror" name="gender">
                                <option value="male" {{ $data->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ $data->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ $data->gender == 'other' ? 'selected' : '' }}>Khác</option>
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
                                id="birth_date" name="birth_date" value="{{ $data->birth_date ?? old('birth_date') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="date_registered" class="form-label">
                                <i class="bi bi-calendar-check-fill"></i> Ngày Quy Y
                            </label>
                            <input type="date"
                                class="form-control input-custom @error('date_registered') is-invalid @enderror"
                                id="date_registered" name="date_registered"
                                value="{{ old('date_registered', \Carbon\Carbon::parse($data->date_registered ?? now())->format('Y-m-d')) }}">
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
                            <input type="text" class="form-control input-custom @error('phone') is-invalid @enderror"
                                id="phone_number" name="phone" value="{{ $data->phone ?? old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-fill"></i> Email
                            </label>
                            <input type="email" class="form-control input-custom @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{  $data->email ?? old('email') }}">
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
                            <select data-selected="{{ $data->country ?? old('province') }}"
                                class="form-control input-custom @error('province') is-invalid @enderror" name="province"
                                id="province">
                                <option value=""></option>
                            </select>
                            @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="district" class="form-label">
                                <i class="bi bi-map-fill"></i> Quận/Huyện
                            </label>
                            <select name="district" data-selected="{{ $data->city ?? old('district') }}" id="district"
                                class="form-control input-custom @error('district') is-invalid @enderror">
                                <option value=""></option>
                            </select>
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="ward" class="form-label">
                                <i class="bi bi-house-door-fill"></i> Phường/Xã
                            </label>
                            <select name="ward" id="ward" data-selected="{{ $data->state ?? old('ward') }}"
                                class="form-control input-custom @error('ward') is-invalid @enderror">
                                <option value=""></option>
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
                            id="address" name="address" value="{{$data->address ?? old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 16px;">
                            <i class="bi bi-send-fill"></i> Chỉnh sửa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/quyy.js') }}"></script>
@endpush