$(document).ready(function () {
    let data = {};
    fetch('https://raw.githubusercontent.com/minhtri1610/Resource_VNJson/refs/heads/master/vn.json')
        .then(response => response.json())
        .then(json => {
            data = json;

            const provinceSelect = document.getElementById('province');
            const topProvince = 'Tỉnh Bình Định';
            if (provinceSelect) {
                let provinces = Object.keys(data).map(key => ({
                    code: key,
                    name: data[key].Name
                }));
    
                // Tìm vị trí của tỉnh được chọn và đưa lên đầu
                provinces.sort((a, b) => (a.name === topProvince ? -1 : b.name === topProvince ? 1 : 0));
    
                // Render danh sách vào select
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));

    document.getElementById('province').addEventListener('change', function () {
        const provinceCode = this.value;
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        if (districtSelect) {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        }
        if (wardSelect) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        }
        if (districtSelect && districtSelect.disabled !== undefined) {
            districtSelect.disabled = true;
        }
        if (wardSelect && wardSelect.disabled !== undefined) {
            wardSelect.disabled = true;
        }
        
        if (provinceCode) {
            const districts = data[provinceCode] ? data[provinceCode].Districts : {};
            Object.keys(districts).forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = districts[district].Name;
                if (districtSelect && districtSelect.appendChild) {
                    districtSelect.appendChild(option);
                }
            });
            if (districtSelect && districtSelect.disabled !== undefined) {
                districtSelect.disabled = false;
            }
        }
    });

    document.getElementById('district').addEventListener('change', function () {
        const provinceCode = document.getElementById('province').value;
        const districtCode = this.value;
        const wardSelect = document.getElementById('ward');
        if (wardSelect) {
            wardSelect.innerHTML = '<option value="">Chon phường/xã</option>';
        }
        if (wardSelect && wardSelect.disabled !== undefined) {
            wardSelect.disabled = true;
        }
        
        if (provinceCode && districtCode) {
            const wards = data[provinceCode] ? data[provinceCode].Districts[districtCode] ? data[provinceCode].Districts[districtCode].Wards : {} : {};
            Object.keys(wards).forEach(ward => {
                const option = document.createElement('option');
                option.value = ward;
                option.textContent = wards[ward].Name;
                if (wardSelect && wardSelect.appendChild) {
                    wardSelect.appendChild(option);
                }
            });
            if (wardSelect && wardSelect.disabled !== undefined) {
                wardSelect.disabled = false;
            }
        }
    });
})

