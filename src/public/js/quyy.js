$(document).ready(function () {
    let data = {};
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    const selectedProvince = normalizeName(provinceSelect?.getAttribute('data-selected'));
    const selectedDistrict = normalizeName(districtSelect?.getAttribute('data-selected'));
    const selectedWard = normalizeName(wardSelect?.getAttribute('data-selected'));

    fetch('https://raw.githubusercontent.com/minhtri1610/Resource_VNJson/refs/heads/master/vn.json')
        .then(response => response.json())
        .then(json => {
            data = json;

            const topProvince = 'Tỉnh Bình Định';
            if (provinceSelect) {
                let provinces = Object.values(data).map(province => province.Name);
                provinces.sort((a, b) => (a === topProvince ? -1 : b === topProvince ? 1 : 0));

                provinces.forEach(provinceName => {
                    const option = document.createElement('option');
                    option.value = normalizeName(provinceName); // Giá trị không có tiền tố
                    option.textContent = provinceName; // Hiển thị có tiền tố
                    if (normalizeName(provinceName) === selectedProvince) option.selected = true;
                    provinceSelect.appendChild(option);
                });

                if (selectedProvince) {
                    loadDistricts(selectedProvince, selectedDistrict, selectedWard);
                }
            }
        })
        .catch(error => console.error('Error:', error));

    provinceSelect?.addEventListener('change', function () {
        const provinceName = this.value;
        loadDistricts(provinceName);
    });

    districtSelect?.addEventListener('change', function () {
        const provinceName = provinceSelect.value;
        const districtName = this.value;
        loadWards(provinceName, districtName);
    });

    function loadDistricts(provinceNameRaw, selectedDistrict = null, selectedWard = null) {
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardSelect.disabled = true;

        const province = Object.values(data).find(p => normalizeName(p.Name) === provinceNameRaw);
        if (province) {
            const districts = Object.values(province.Districts);
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = normalizeName(district.Name);
                option.textContent = district.Name;
                if (normalizeName(district.Name) === selectedDistrict) option.selected = true;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;

            if (selectedDistrict) {
                loadWards(provinceNameRaw, selectedDistrict, selectedWard);
            }
        }
    }

    function loadWards(provinceNameRaw, districtNameRaw, selectedWard = null) {
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

        const province = Object.values(data).find(p => normalizeName(p.Name) === provinceNameRaw);
        if (province) {
            const district = Object.values(province.Districts).find(d => normalizeName(d.Name) === districtNameRaw);
            if (district) {
                const wards = Object.values(district.Wards);
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = normalizeName(ward.Name);
                    option.textContent = ward.Name;
                    if (normalizeName(ward.Name) === selectedWard) option.selected = true;
                    wardSelect.appendChild(option);
                });
                wardSelect.disabled = false;
            }
        }
    }

    function normalizeName(name) {
        return name
            ?.replace(/^(Tỉnh|Thành phố|TP\.?|Quận|Huyện|Thị xã|Phường|Xã|Thị trấn)\s*/gi, '')
            .trim();
    }
});
