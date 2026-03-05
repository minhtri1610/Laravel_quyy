@extends('client.layouts.main')

@push('css')
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Imperial+Script&display=swap"
        rel="stylesheet">
    <style>
        .certificate-wrapper {
            padding: 3rem 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .certificate {
            position: relative;
            background-color: #fcf8e8;
            /* Pale yellow/cream reminiscent of old paper */
            background-image: url('https://www.transparenttextures.com/patterns/rice-paper-3.png');
            /* Ornate frame effect */
            border: 2px solid #8b0000;
            /* Deep Buddhist Red (Đỏ bã trầu) */
            outline: 6px solid #d4af37;
            /* Metallic Gold */
            outline-offset: -12px;
            padding: 60px 45px 50px;
            width: 700px;
            /* Fixed width to mimic PC view on mobile */
            min-width: 700px;
            border-radius: 4px;
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.25),
                inset 0 0 0 16px #fcf8e8,
                inset 0 0 0 17px rgba(139, 0, 0, 0.4);
            margin: 4vh auto;
            overflow: hidden;
            z-index: 1;
        }

        /* Large water lotus in the background */
        .certificate::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 70%;
            height: 70%;
            transform: translate(-50%, -50%);
            background-image: url("{{asset('/images/lotus-icon.png')}}");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.08;
            filter: sepia(100%) hue-rotate(330deg) saturate(200%);
            z-index: -1;
        }

        .certificate-content {
            position: relative;
            z-index: 2;
        }

        .certificate header {
            text-align: center;
            margin-bottom: 30px;
        }

        .certificate header .buddhist-org {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 600;
            color: #8b0000;
            margin: 0 0 4px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .certificate header .pagoda-name {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 600;
            color: #b8860b;
            /* Dark Goldenrod */
            margin: 0;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .certificate .divider-icon {
            text-align: center;
            color: #d4af37;
            font-size: 22px;
            margin: 10px 0 15px;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
        }

        .certificate h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            text-transform: uppercase;
            margin: 15px 0 25px;
            color: #d32f2f;
            font-size: 42px;
            letter-spacing: 6px;
            text-shadow: 2px 2px 0px #fff, 3px 3px 4px rgba(0, 0, 0, 0.15);
        }

        .logo-center {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo-center img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        .certificate .qr_code {
            position: absolute;
            top: 45px;
            right: 45px;
            background: #fff;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid rgba(212, 175, 55, 0.5);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .certificate .qr_code svg {
            width: 70px;
            height: 70px;
            display: block;
        }

        .details {
            font-family: 'Lora', serif;
            font-size: 19px;
            line-height: 1.7;
            color: #4e342e;
            /* Dark brown for formal text */
        }

        .details .info-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 18px;
        }

        .details .info-row .label {
            font-weight: 500;
            color: #8b0000;
            margin-right: 15px;
            white-space: nowrap;
            font-style: italic;
        }

        .details .info-row .value {
            flex-grow: 1;
            border-bottom: 1px dotted #8b0000;
            padding-bottom: 2px;
            min-height: 28px;
            font-weight: 500;
        }

        .details .d-name {
            font-family: 'Imperial Script', 'Great Vibes', cursive;
            font-size: 46px;
            color: #b71c1c;
            line-height: 0.7;
            transform: translateY(6px);
            display: inline-block;
            font-weight: 400;
            letter-spacing: 6px;
            word-spacing: 10px;
            text-align: center;
            font-style: normal;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-family: 'Lora', serif;
            font-size: 16px;
            color: #4e342e;
            margin-top: 30px;
            border-top: 1px solid rgba(212, 175, 55, 0.4);
            padding-top: 20px;
        }

        .footer .left-section {
            text-align: left;
        }

        .footer .right-section {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer p {
            margin: 0;
            font-style: italic;
        }

        .footer .val {
            font-style: normal;
            font-weight: 600;
            color: #8b0000;
        }

        .stamp-placeholder {
            text-align: center;
            margin-top: 10px;
        }

        .stamp-placeholder .text {
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            color: #b8860b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0px;
        }

        .buddhist-vows {
            margin-top: 30px;
            font-family: 'Lora', serif;
            color: #4e342e;
        }

        .three-refuges {
            text-align: left;
            margin-bottom: 25px;
        }

        .three-refuges p {
            margin: 4px 0;
            font-size: 14.5px;
            font-weight: 600;
            color: #b71c1c;
            text-transform: uppercase;
        }

        .vows-grid {
            display: flex;
            justify-content: space-between;
            gap: 25px;
            border-top: 1px dotted rgba(139, 0, 0, 0.4);
            padding-top: 20px;
            font-size: 13.5px;
        }

        .vows-col {
            flex: 1;
        }

        .vows-col h4 {
            font-family: 'Playfair Display', serif;
            color: #b8860b;
            font-size: 15.5px;
            font-weight: 700;
            margin: 0 0 12px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .vows-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .vows-col ul li {
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .seven-vows-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 10px;
        }

        @media (max-width: 600px) {
            .certificate-wrapper {
                justify-content: flex-start;
                padding: 1rem 15px;
                overflow-x: auto;
                /* Enable scrolling on smaller screens wrapper */
            }

            .certificate {
                transform-origin: top left;
                /* Note: We keep original PC dimensions to allow zoom and pan */
            }
        }
    </style>
@endpush
@section('content')
    <div class="container search-home">
        <div class="certificate-wrapper">
            <div class="certificate">

                <div class="qr_code tooltip-container" style="text-align: center;">
                    <div id="qr-svg-code">
                        {!! $user->qr_code !!}
                    </div>
                    <button class="btn btn-sm btn-outline-warning mt-2" onclick="downloadQRCode()"
                        style="font-size: 12px; padding: 2px 8px; border-radius: 10px;">
                        <i class="bi bi-download"></i> Tải QR
                    </button>
                </div>

                <div class="certificate-content">
                    <header>
                        <div class="logo-center">
                            <img src="{{asset('/images/logo-sm.png')}}" alt="Logo">
                        </div>
                        <p class="buddhist-org">Giáo Hội Phật Giáo Việt Nam</p>
                        <p class="pagoda-name">Bổn Tự: Chùa Phước Lộc</p>
                        <div class="divider-icon">
                            ❖ ❁ ❖
                        </div>
                        <h1>QUY Y TAM BẢO</h1>
                    </header>

                    <div class="details">
                        <div class="buddhist-vows">
                            <div class="three-refuges">
                                <p>1. QUY Y PHẬT - ĐỆ TỬ NGUYỆN TRỌN ĐỜI TÔN THỜ ĐẤNG CHÁNH GIÁC</p>
                                <p>2. QUY Y PHÁP - ĐỆ TỬ NGUYỆN TRỌN ĐỜI TÔN THỜ LỜI PHẬT DẠY</p>
                                <p>3. QUY Y TĂNG - ĐỆ TỬ NGUYỆN TRỌN ĐỜI TÔN THỜ NHỮNG VỊ XUẤT GIA TU HÀNH CHÂN CHÍNH</p>
                            </div>
                            <div class="vows-grid">
                                <div class="vows-col">
                                    <h4>Năm Giới Cấm</h4>
                                    <ul>
                                        <li>1 - Không sát sanh hại vật.</li>
                                        <li>2 - Không gian tham trộm cướp.</li>
                                        <li>3 - Không tà dâm.</li>
                                        <li>4 - Không nói bậy.</li>
                                        <li>5 - Không dùng chất say nghiện.</li>
                                    </ul>
                                </div>
                                <div class="vows-col">
                                    <h4>Bảy Điều Nguyện</h4>
                                    <ul class="seven-vows-box">
                                        <li>1. Cố gắng tập ăn chay.</li>
                                        <li>4. Nỗ lực làm việc từ thiện.</li>
                                        <li>2. Siêng năng học hỏi giáo pháp.</li>
                                        <li>5. Nguyện Phật hoá gia đình.</li>
                                        <li>3. Thường xuyên toạ thiền, niệm Phật, lễ Phật.</li>
                                        <li>6. Nguyện giúp mọi người tin hiểu Phật Pháp.</li>
                                        <li style="grid-column: 1 / -1;">7. Nguyện kiên cường hộ đạo.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="label">Nay đệ tử:</span>
                            <span class="value d-name">{{$user->name}}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Pháp danh:</span>
                            <span class="value d-name">{{$user->nickname ?? '...'}}</span>
                        </div>
                        <div class="info-row" style="flex-direction: row; align-items: flex-end;">
                            <span class="label">Giới tính:</span>
                            <span class="value"
                                style="flex-grow: 0; padding-right: 25px; min-width: 60px;">{{$user->gender == 'male' ? 'Nam' : 'Nữ'}}</span>
                            <span class="label" style="margin-left: 15px;">Ngày sinh:</span>
                            <span
                                class="value">{{$user->birth_date ? date('d/m/Y', strtotime($user->birth_date)) : '...'}}</span>
                        </div>

                        <div class="info-row" style="align-items: flex-start;">
                            <span class="label">Địa chỉ:</span>
                            <span class="value" style="line-height: 1.5; padding-top: 3px;">
                                @if(empty($user->country))
                                    ...
                                @else
                                    @php
                                        $addressParts = array_filter([
                                            $user->address,
                                            $user->state,
                                            $user->city,
                                            $user->country,
                                        ]);

                                        $full_address = implode(' - ', $addressParts);
                                    @endphp
                                    {{$full_address}}
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <p>Xin nguyện trọn đời <strong>QUY Y TAM BẢO</strong>, thọ trì <strong>NĂM GIỚI CẤM</strong> và
                                <strong>BẢY ĐIỀU NGUYỆN</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="footer">
                        <div class="left-section">
                            <p>Số Điệp/Mã: <span class="val">{{$user->uid_code}}</span></p>
                        </div>
                        <div class="right-section">
                            <p>Ngày <span class="val">{{ date('d', strtotime($user->date_registered))}}</span>
                                tháng <span class="val">{{ date('m', strtotime($user->date_registered))}}</span>
                                năm <span class="val">{{ date('Y', strtotime($user->date_registered))}}</span>
                            </p>
                            <div class="stamp-placeholder">
                                <div class="text">
                                    Bổn sư truyền thọ<br>
                                    <span
                                        style="font-size: 16px; font-weight: 700; color: #b71c1c; margin-top: 8px; display: inline-block;">ĐĐ.
                                        Thích Tánh Minh</span>
                                </div>
                                <div class="stamp mb-5">
                                    <img width="250px" height="120px" src="{{asset('/images/stamp.png')}}" alt="Stamp">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function downloadQRCode() {
            const svgElement = document.querySelector('#qr-svg-code svg');
            if (!svgElement) {
                alert("Không tìm thấy mã QR để tải!");
                return;
            }

            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            let svgData = new XMLSerializer().serializeToString(svgElement);
            // Fix some constraints with external SVG loading:
            if (!svgData.includes('xmlns="http://www.w3.org/2000/svg"')) {
                svgData = svgData.replace(/<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
            }

            const img = new Image();
            const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
            const url = URL.createObjectURL(svgBlob);

            img.onload = function () {
                canvas.width = 300; // Tăng độ phân giải cho ảnh tải xuống hiển thị rõ hơn (phóng to)
                canvas.height = 300;

                // Tô nền trắng để chống suốt đối với PNG
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Vẽ QR code lên trên
                ctx.drawImage(img, 15, 15, 270, 270);
                URL.revokeObjectURL(url);

                const imgURI = canvas.toDataURL("image/png");

                const link = document.createElement("a");
                link.href = imgURI;
                link.download = "QR_NhaChua_PhuocLoc_{{ $user->uid_code ?? 'ma-dinh-danh' }}.png";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
            img.src = url;
        }
    </script>
@endpush

@include('client.layouts.menu-bar')