/**
 * Trang đăng nhập quản trị
 *  - Nút hiện / ẩn mật khẩu
 *  - Cảnh báo khi Caps Lock đang bật
 *  - Khóa nút đăng nhập khi đang gửi
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login-form');
    const password = document.getElementById('password');
    const toggle = document.getElementById('toggle-password');
    const capsWarning = document.getElementById('capslock-warning');
    const submitButton = document.getElementById('btn-login');

    // ------------------------------------------------------------ hiện / ẩn mật khẩu
    toggle?.addEventListener('click', function () {
        const show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        toggle.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        toggle.setAttribute('aria-label', show ? toggle.dataset.labelHide : toggle.dataset.labelShow);
        password.focus();
    });

    // ------------------------------------------------------------ Caps Lock
    function updateCapsLock(event) {
        if (!capsWarning || typeof event.getModifierState !== 'function') return;
        capsWarning.classList.toggle('d-none', !event.getModifierState('CapsLock'));
    }

    password?.addEventListener('keydown', updateCapsLock);
    password?.addEventListener('keyup', updateCapsLock);
    password?.addEventListener('blur', () => capsWarning?.classList.add('d-none'));

    // ------------------------------------------------------------ nút đăng nhập
    function setSubmitting(submitting) {
        if (!submitButton) return;
        submitButton.disabled = submitting;
        submitButton.querySelector('.btn-label')?.classList.toggle('d-none', submitting);
        submitButton.querySelector('.btn-loading')?.classList.toggle('d-none', !submitting);
    }

    form?.addEventListener('submit', () => setSubmitting(true));

    // Bấm "Quay lại" của trình duyệt: trang lấy từ cache nên mở lại nút
    window.addEventListener('pageshow', () => setSubmitting(false));
});
