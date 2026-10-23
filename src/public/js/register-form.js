/**
 * Form đăng ký Quy Y (client/temporary-users/create)
 *  - Gộp 3 ô Ngày / Tháng / Năm vào input ẩn birth_date (Y-m-d)
 *  - Khóa nút gửi khi đang gửi để tránh bấm nhiều lần
 *  - Có lỗi thì cuộn tới ô lỗi đầu tiên
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('register-form');
    if (!form) return;

    // ------------------------------------------------------------ ngày sinh
    const daySelect = document.getElementById('birth_day');
    const monthSelect = document.getElementById('birth_month');
    const yearSelect = document.getElementById('birth_year');
    const birthInput = document.getElementById('birth_date');

    const pad = n => String(n).padStart(2, '0');
    const daysInMonth = (month, year) => new Date(year || 2000, month, 0).getDate(); // 2000 là năm nhuận

    function syncBirthDate() {
        const day = parseInt(daySelect.value, 10);
        const month = parseInt(monthSelect.value, 10);
        const year = parseInt(yearSelect.value, 10);

        // Ẩn các ngày không có trong tháng (vd 30, 31 của tháng 2)
        const maxDay = month ? daysInMonth(month, year) : 31;
        Array.from(daySelect.options).forEach(option => {
            const value = parseInt(option.value, 10);
            const invalid = value > maxDay;
            option.disabled = invalid;
            option.hidden = invalid;
        });
        if (day > maxDay) {
            daySelect.value = String(maxDay);
        }

        const finalDay = parseInt(daySelect.value, 10);
        birthInput.value = finalDay && month && year ? `${year}-${pad(month)}-${pad(finalDay)}` : '';
    }

    if (daySelect && monthSelect && yearSelect && birthInput) {
        [daySelect, monthSelect, yearSelect].forEach(select => select.addEventListener('change', syncBirthDate));
        syncBirthDate();
    }

    // ------------------------------------------------------------ nút gửi
    const submitButton = document.getElementById('btn-submit');

    function setSubmitting(submitting) {
        if (!submitButton) return;
        submitButton.disabled = submitting;
        submitButton.querySelector('.btn-label')?.classList.toggle('d-none', submitting);
        submitButton.querySelector('.btn-loading')?.classList.toggle('d-none', !submitting);
    }

    form.addEventListener('submit', function () {
        setSubmitting(true);
    });

    // Bấm "Quay lại" của trình duyệt sau khi gửi: trang lấy từ cache nên mở lại nút
    window.addEventListener('pageshow', function () {
        setSubmitting(false);
    });

    // ------------------------------------------------------------ lỗi
    const firstInvalid = form.querySelector('.is-invalid');
    if (firstInvalid) {
        const target = firstInvalid.matches('input, select, textarea')
            ? firstInvalid
            : firstInvalid.querySelector('input, select, textarea') || firstInvalid;
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        if (typeof target.focus === 'function') {
            target.focus({ preventScroll: true });
        }
    }
});
