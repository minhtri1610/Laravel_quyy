/**
 * Chọn ảnh đại diện (resources/views/admin/quyy/_avatar-field.blade.php)
 *  - Xem trước ảnh đã chọn
 *  - Tự thu nhỏ ảnh lớn (cạnh dài tối đa MAX_SIZE px) trước khi tải lên
 *  - Nút "Xóa ảnh": bỏ ảnh vừa chọn / đánh dấu xóa ảnh hiện tại
 */
document.addEventListener('DOMContentLoaded', function () {
    const MAX_SIZE = 800;                    // px
    const MAX_BYTES = 5 * 1024 * 1024;       // khớp rule max:5120 phía server
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    document.querySelectorAll('[data-avatar-field]').forEach(initAvatarField);

    function initAvatarField(field) {
        const input = field.querySelector('[data-avatar-input]');
        const img = field.querySelector('[data-avatar-img]');
        const placeholder = field.querySelector('[data-avatar-placeholder]');
        const removeButton = field.querySelector('[data-avatar-remove]');
        const removeInput = field.querySelector('[data-avatar-remove-input]');
        const chooseText = field.querySelector('[data-avatar-choose-text]');
        const errorBox = field.querySelector('[data-avatar-error]');
        const preview = field.querySelector('.avatar-preview');
        const initialUrl = img.getAttribute('src') || null;
        let objectUrl = null;

        input.addEventListener('change', async function () {
            const file = input.files[0];
            if (!file) {
                // Bấm "Hủy" trong hộp chọn file (Chrome xóa luôn file đã chọn): trở về ảnh ban đầu
                hideError();
                removeInput.value = '0';
                return showImage(initialUrl);
            }

            if (!ALLOWED_TYPES.includes(file.type)) {
                clearInput();
                return showError('Chỉ nhận ảnh định dạng JPG, PNG hoặc WEBP.');
            }

            let finalFile = file;
            try {
                finalFile = await downscale(file);
                if (finalFile !== file) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(finalFile);
                    input.files = dataTransfer.files;
                }
            } catch (error) {
                // Trình duyệt không hỗ trợ: gửi ảnh gốc, server vẫn kiểm tra dung lượng
                console.warn('Không thu nhỏ được ảnh:', error);
            }

            if (finalFile.size > MAX_BYTES) {
                clearInput();
                return showError('Ảnh vượt quá 5MB, vui lòng chọn ảnh khác.');
            }

            hideError();
            removeInput.value = '0';
            showImage(URL.createObjectURL(finalFile), true);
        });

        removeButton?.addEventListener('click', function () {
            clearInput();
            hideError();
            removeInput.value = '1';
            showImage(null);
        });

        function showImage(url, isObjectUrl = false) {
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = isObjectUrl ? url : null;

            img.classList.toggle('d-none', !url);
            placeholder.classList.toggle('d-none', !!url);
            removeButton?.classList.toggle('d-none', !url);
            if (url) img.src = url; else img.removeAttribute('src');
            if (chooseText) chooseText.textContent = url ? 'Đổi ảnh' : 'Chọn ảnh';
        }

        function clearInput() {
            input.value = '';
        }

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.remove('d-none');
            preview.classList.add('is-invalid');
        }

        function hideError() {
            errorBox.textContent = '';
            errorBox.classList.add('d-none');
            preview.classList.remove('is-invalid');
        }
    }

    /**
     * Thu nhỏ ảnh về cạnh dài MAX_SIZE px, xuất JPEG.
     * Ảnh đã đủ nhỏ thì trả lại file gốc.
     */
    async function downscale(file) {
        const bitmap = await createImageBitmap(file);
        const scale = Math.min(1, MAX_SIZE / Math.max(bitmap.width, bitmap.height));
        if (scale === 1 && file.size <= 1024 * 1024) {
            bitmap.close?.();
            return file;
        }

        const canvas = document.createElement('canvas');
        canvas.width = Math.round(bitmap.width * scale);
        canvas.height = Math.round(bitmap.height * scale);
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff'; // nền trắng cho ảnh PNG trong suốt
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(bitmap, 0, 0, canvas.width, canvas.height);
        bitmap.close?.();

        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.85));
        if (!blob) throw new Error('canvas.toBlob trả về null');

        const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
        return new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
    }
});
