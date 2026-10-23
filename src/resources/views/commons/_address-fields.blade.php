{{--
    Chọn Tỉnh/Thành - (Quận/Huyện) - Phường/Xã, dùng kèm public/js/quyy.js
    Biến:
        $province, $district, $ward : giá trị đã chọn (tên không có tiền tố)
        $useOldAddress              : true = địa chỉ cũ (có Quận/Huyện)
--}}
@php
    $useOldAddress = $useOldAddress ?? false;
    $colClass = $useOldAddress ? 'col-md-4' : 'col-md-6';
@endphp

<div class="mb-2">
    {{-- form-switch: Bootstrap 5 hiển thị dạng công tắc, Bootstrap 4 (admin) bỏ qua --}}
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="address_type" value="old" id="use_old_address"
            {{ $useOldAddress ? 'checked' : '' }}>
        <label class="form-check-label" for="use_old_address">
            Nhập theo địa chỉ cũ (có Quận/Huyện)
        </label>
    </div>
    <small class="text-muted">
        Từ 01/07/2025, địa chỉ hành chính chỉ còn Tỉnh/Thành phố và Phường/Xã, không còn Quận/Huyện.
    </small>
</div>

<div class="row mb-3">
    <div class="{{ $colClass }} address-col">
        <label for="province" class="form-label">
            <i class="bi bi-geo-alt-fill"></i> Tỉnh/Thành Phố
        </label>
        <select name="province" id="province" data-selected="{{ $province ?? '' }}"
            class="form-control input-custom @error('province') is-invalid @enderror">
            <option value=""></option>
        </select>
        @error('province')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 {{ $useOldAddress ? '' : 'd-none' }}" id="district-col">
        <label for="district" class="form-label">
            <i class="bi bi-map-fill"></i> Quận/Huyện
        </label>
        <select name="district" id="district" data-selected="{{ $district ?? '' }}"
            class="form-control input-custom @error('district') is-invalid @enderror"
            {{ $useOldAddress ? '' : 'disabled' }}>
            <option value=""></option>
        </select>
        @error('district')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="{{ $colClass }} address-col">
        <label for="ward" class="form-label">
            <i class="bi bi-house-door-fill"></i> Phường/Xã
        </label>
        <select name="ward" id="ward" data-selected="{{ $ward ?? '' }}"
            class="form-control input-custom @error('ward') is-invalid @enderror">
            <option value=""></option>
        </select>
        @error('ward')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
