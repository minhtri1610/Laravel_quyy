$(document).ready(function () {
    let data = {};
    fetch('https://raw.githubusercontent.com/minhtri1610/Resource_VNJson/refs/heads/master/vn.json')
        .then(response => response.json())
        .then(json => {
            data = json;

            const provinceSelect = document.getElementById('province');
            const topProvince = 'Tỉnh Bình Định';
            if (provinceSelect) {
                let provinces = Object.values(data).map(province => province.Name);
                
                // Sắp xếp đưa tỉnh Bình Định lên đầu
                provinces.sort((a, b) => (a === topProvince ? -1 : b === topProvince ? 1 : 0));
                
                // Render danh sách vào select
                provinces.forEach(provinceName => {
                    const option = document.createElement('option');
                    option.value = provinceName; // Lưu bằng text
                    option.textContent = provinceName;
                    provinceSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));

    document.getElementById('province').addEventListener('change', function () {
        const provinceName = this.value;
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        if (districtSelect) {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        }
        if (wardSelect) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        }
        districtSelect.disabled = true;
        wardSelect.disabled = true;
        
        const province = Object.values(data).find(p => p.Name === provinceName);
        if (province) {
            const districts = Object.values(province.Districts);
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.Name; // Lưu bằng text
                option.textContent = district.Name;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        }
    });

    document.getElementById('district').addEventListener('change', function () {
        const provinceName = document.getElementById('province').value;
        const districtName = this.value;
        const wardSelect = document.getElementById('ward');
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardSelect.disabled = true;
        
        const province = Object.values(data).find(p => p.Name === provinceName);
        if (province) {
            const district = Object.values(province.Districts).find(d => d.Name === districtName);
            if (district) {
                const wards = Object.values(district.Wards);
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.Name; // Lưu bằng text
                    option.textContent = ward.Name;
                    wardSelect.appendChild(option);
                });
                wardSelect.disabled = false;
            }
        }
    });
});