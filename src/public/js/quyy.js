/**
 * Chọn địa chỉ Tỉnh/Thành - (Quận/Huyện) - Phường/Xã.
 *
 * Hai chế độ, chuyển bằng checkbox #use_old_address:
 *  - Địa chỉ mới (từ 01/07/2025, không còn cấp huyện): Tỉnh/Thành -> Phường/Xã
 *  - Địa chỉ cũ: Tỉnh/Thành -> Quận/Huyện -> Phường/Xã
 * Trang không có checkbox thì luôn dùng địa chỉ cũ.
 *
 * Giá trị lưu là tên đã bỏ tiền tố (vd "Tỉnh Gia Lai" -> "Gia Lai").
 * Giá trị đã chọn trước đó lấy từ thuộc tính data-selected của từng select.
 */
$(document).ready(function () {
    const OLD_DATA_URL = 'https://raw.githubusercontent.com/minhtri1610/Resource_VNJson/refs/heads/master/vn.json';
    const NEW_API_URL = 'https://provinces.open-api.vn/api/v2';
    const TOP_PROVINCE = { old: 'Bình Định', new: 'Gia Lai' };

    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const oldAddressCheckbox = document.getElementById('use_old_address');
    const districtCol = document.getElementById('district-col');
    const addressCols = document.querySelectorAll('.address-col');

    if (!provinceSelect || !wardSelect) {
        return;
    }

    // Chỉ dùng cho lần tải đầu tiên, đổi chế độ thì bỏ
    let selected = {
        province: normalizeName(provinceSelect.getAttribute('data-selected')),
        district: normalizeName(districtSelect?.getAttribute('data-selected')),
        ward: normalizeName(wardSelect.getAttribute('data-selected')),
    };

    let oldData = null;          // { code: { Name, Districts: { code: { Name, Wards } } } }
    let newProvinces = null;     // [{ name, code }]
    const newWardsCache = {};    // provinceCode -> [ward name]
    let loadToken = 0;           // bỏ qua kết quả fetch đã cũ khi người dùng đổi nhanh

    const isOldMode = () => !oldAddressCheckbox || oldAddressCheckbox.checked;

    // Người dùng tự chọn thì không dùng giá trị đã lưu nữa
    const clearSelected = () => (selected = { province: null, district: null, ward: null });

    oldAddressCheckbox?.addEventListener('change', function () {
        clearSelected();
        applyMode();
    });

    provinceSelect.addEventListener('change', function () {
        clearSelected();
        if (isOldMode()) {
            loadOldDistricts(this.value);
        } else {
            loadNewWards(this.value);
        }
    });

    districtSelect?.addEventListener('change', function () {
        clearSelected();
        loadOldWards(provinceSelect.value, this.value);
    });

    applyMode();

    function applyMode() {
        const old = isOldMode();

        // Ẩn cột Quận/Huyện ở chế độ mới; disabled để không gửi lên server
        if (districtCol) {
            districtCol.classList.toggle('d-none', !old);
        }
        if (districtSelect) {
            districtSelect.disabled = !old;
        }
        addressCols.forEach(col => {
            col.classList.toggle('col-md-4', old);
            col.classList.toggle('col-md-6', !old);
        });

        resetSelect(districtSelect, 'Chọn quận/huyện');
        resetSelect(wardSelect, 'Chọn phường/xã');
        wardSelect.disabled = true;
        if (old && districtSelect) {
            districtSelect.disabled = true;
        }

        if (old) {
            loadOldProvinces();
        } else {
            loadNewProvinces();
        }
    }

    // ------------------------------------------------------------ địa chỉ cũ

    function loadOldProvinces() {
        const token = ++loadToken;
        setLoading(provinceSelect);

        const dataPromise = oldData
            ? Promise.resolve(oldData)
            : fetchJson(OLD_DATA_URL).then(json => (oldData = json));

        dataPromise
            .then(data => {
                if (token !== loadToken) return;
                const names = Object.values(data).map(p => p.Name);
                fillSelect(provinceSelect, 'Chọn tỉnh/thành phố', sortTop(names, TOP_PROVINCE.old), selected.province);
                if (selected.province) {
                    loadOldDistricts(selected.province);
                }
            })
            .catch(() => token === loadToken && setError(provinceSelect));
    }

    function loadOldDistricts(provinceName) {
        resetSelect(wardSelect, 'Chọn phường/xã');
        wardSelect.disabled = true;

        const province = findOldProvince(provinceName);
        const names = province ? Object.values(province.Districts).map(d => d.Name) : [];
        fillSelect(districtSelect, 'Chọn quận/huyện', names, selected.district);
        districtSelect.disabled = names.length === 0;

        if (selected.district) {
            loadOldWards(provinceName, selected.district);
        }
    }

    function loadOldWards(provinceName, districtName) {
        const province = findOldProvince(provinceName);
        const district = province
            ? Object.values(province.Districts).find(d => normalizeName(d.Name) === districtName)
            : null;
        const names = district ? Object.values(district.Wards).map(w => w.Name) : [];
        fillSelect(wardSelect, 'Chọn phường/xã', names, selected.ward);
        wardSelect.disabled = names.length === 0;
    }

    function findOldProvince(provinceName) {
        return oldData
            ? Object.values(oldData).find(p => normalizeName(p.Name) === provinceName)
            : null;
    }

    // ------------------------------------------------------------ địa chỉ mới

    function loadNewProvinces() {
        const token = ++loadToken;
        setLoading(provinceSelect);

        const dataPromise = newProvinces
            ? Promise.resolve(newProvinces)
            : fetchJson(`${NEW_API_URL}/p/`).then(json => (newProvinces = json.map(p => ({ name: p.name, code: p.code }))));

        dataPromise
            .then(provinces => {
                if (token !== loadToken) return;
                const names = provinces.map(p => p.name);
                if (!containsName(names, selected.province)) {
                    return fallbackToOldMode();
                }
                fillSelect(provinceSelect, 'Chọn tỉnh/thành phố', sortTop(names, TOP_PROVINCE.new), selected.province);
                if (selected.province) {
                    loadNewWards(selected.province);
                }
            })
            .catch(() => token === loadToken && setError(provinceSelect));
    }

    function loadNewWards(provinceName) {
        const province = newProvinces?.find(p => normalizeName(p.name) === provinceName);
        if (!province) {
            resetSelect(wardSelect, 'Chọn phường/xã');
            wardSelect.disabled = true;
            return;
        }

        const token = ++loadToken;
        setLoading(wardSelect);

        const wardsPromise = newWardsCache[province.code]
            ? Promise.resolve(newWardsCache[province.code])
            : fetchJson(`${NEW_API_URL}/p/${province.code}?depth=2`)
                .then(json => (newWardsCache[province.code] = (json.wards || []).map(w => w.name)));

        wardsPromise
            .then(names => {
                if (token !== loadToken) return;
                if (!containsName(names, selected.ward)) {
                    return fallbackToOldMode();
                }
                fillSelect(wardSelect, 'Chọn phường/xã', names, selected.ward);
                wardSelect.disabled = names.length === 0;
            })
            .catch(() => token === loadToken && setError(wardSelect));
    }

    // Giá trị đã lưu không có trong danh sách địa chỉ mới (vd "Bình Định"):
    // đây là địa chỉ cũ, chuyển sang chế độ cũ để không làm mất dữ liệu khi lưu lại
    function fallbackToOldMode() {
        if (oldAddressCheckbox) {
            oldAddressCheckbox.checked = true;
            applyMode();
        }
    }

    function containsName(names, selectedName) {
        return !selectedName || names.some(name => normalizeName(name) === selectedName);
    }

    // ------------------------------------------------------------ helpers

    function fetchJson(url) {
        return fetch(url).then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${url}`);
            return response.json();
        }).catch(error => {
            console.error('Không tải được dữ liệu địa chỉ:', error);
            throw error;
        });
    }

    function fillSelect(select, placeholder, names, selectedName) {
        if (!select) return;
        resetSelect(select, placeholder);
        names.forEach(name => {
            const option = document.createElement('option');
            option.value = normalizeName(name); // Giá trị không có tiền tố
            option.textContent = name;          // Hiển thị có tiền tố
            if (selectedName && option.value === selectedName) option.selected = true;
            select.appendChild(option);
        });
        select.disabled = false;
    }

    function resetSelect(select, placeholder) {
        if (!select) return;
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
    }

    function setLoading(select) {
        select.innerHTML = '<option value="">Đang tải...</option>';
        select.disabled = true;
    }

    function setError(select) {
        select.innerHTML = '<option value="">Không tải được dữ liệu, vui lòng tải lại trang</option>';
        select.disabled = true;
    }

    function sortTop(names, topName) {
        return names.sort((a, b) => {
            const aTop = normalizeName(a) === topName;
            const bTop = normalizeName(b) === topName;
            return aTop === bTop ? 0 : aTop ? -1 : 1;
        });
    }

    function normalizeName(name) {
        return name
            ?.replace(/^(Tỉnh|Thành phố|TP\.?|Quận|Huyện|Thị xã|Phường|Xã|Thị trấn|Đặc khu)\s*/i, '')
            .trim() || null;
    }
});
