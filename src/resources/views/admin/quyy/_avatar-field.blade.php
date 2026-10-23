{{--
    Chọn ảnh đại diện, dùng kèm public/js/avatar-upload.js
    Biến:
        $avatarUrl : URL ảnh hiện tại (null nếu chưa có)
    Gửi lên: avatar_file (file), remove_avatar (1 = xóa ảnh hiện tại)
    Form chứa partial này phải có enctype="multipart/form-data"
--}}
@php
    $avatarUrl = $avatarUrl ?? null;
@endphp

<div class="avatar-field mb-4" data-avatar-field>
    <div class="avatar-preview @error('avatar_file') is-invalid @enderror">
        <img src="{{ $avatarUrl }}" alt="Ảnh đại diện" class="{{ $avatarUrl ? '' : 'd-none' }}" data-avatar-img>
        <i class="bi bi-person-fill {{ $avatarUrl ? 'd-none' : '' }}" data-avatar-placeholder></i>
    </div>

    <div class="avatar-body">
        <span class="form-label d-block mb-2"><i class="bi bi-image"></i> Ảnh đại diện</span>
        <div class="avatar-actions">
            <label for="avatar_file" class="btn btn-sm btn-outline-primary mb-0">
                <i class="bi bi-upload"></i> <span data-avatar-choose-text>{{ $avatarUrl ? 'Đổi ảnh' : 'Chọn ảnh' }}</span>
            </label>
            <button type="button" class="btn btn-sm btn-outline-danger {{ $avatarUrl ? '' : 'd-none' }}" data-avatar-remove>
                <i class="bi bi-trash"></i> Xóa ảnh
            </button>
        </div>
        <input type="file" id="avatar_file" name="avatar_file" class="d-none"
            accept="image/jpeg,image/png,image/webp" data-avatar-input>
        <input type="hidden" name="remove_avatar" value="0" data-avatar-remove-input>

        <small class="text-muted d-block mt-2">JPG, PNG hoặc WEBP, tối đa 5MB. Ảnh lớn sẽ được tự thu nhỏ trước khi tải lên.</small>
        <div class="invalid-feedback d-block {{ $errors->has('avatar_file') ? '' : 'd-none' }}" data-avatar-error>
            {{ $errors->first('avatar_file') }}
        </div>
    </div>
</div>
