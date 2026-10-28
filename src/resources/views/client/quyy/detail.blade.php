@extends('client.layouts.main')

@php
    $genderLabels = ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'];
    $gender = $genderLabels[$user->gender] ?? '...';
    $birthDate = $user->birth_date ? date('d/m/Y', strtotime($user->birth_date)) : '...';
    $fullAddress = convert_address($user) ?: '...';
    $registered = $user->date_registered ? strtotime($user->date_registered) : null;
    $shareUrl = route('client.quyy.short_detail', ['uid' => $user->uid]);
    $fileName = 'PhieuQuyY_ChuaPhuocLoc_' . ($user->uid_code ?: 'phat-tu');
@endphp

@section('title', 'Phiếu Quy Y - ' . $user->name . ' - Chùa Phước Lộc')

@section('content')
<div class="cert-page">
    {{-- Đầu trang + thanh công cụ --}}
    <section class="pl-theme cert-head">
        <div class="container text-center">
            <span class="pl-eyebrow">Chùa Phước Lộc</span>
            <h1 class="cert-page-title">Phiếu Quy Y Tam Bảo</h1>
            <p class="cert-page-subtitle">
                Phật tử <strong>{{ $user->name }}</strong>
                @if ($user->nickname)
                    &middot; Pháp danh <strong>{{ $user->nickname }}</strong>
                @endif
            </p>

            <div class="cert-toolbar" role="toolbar" aria-label="Thao tác với phiếu">
                <button type="button" class="pl-btn pl-btn-primary" id="btn-download-cert">
                    <i class="bi bi-image"></i> <span class="btn-text">Lưu ảnh phiếu</span>
                </button>
                <button type="button" class="pl-btn" id="btn-print-cert">
                    <i class="bi bi-printer"></i> In phiếu
                </button>
                <button type="button" class="pl-btn" id="btn-download-qr">
                    <i class="bi bi-qr-code"></i> Tải mã QR
                </button>
                <button type="button" class="pl-btn" id="btn-share-cert">
                    <i class="bi bi-share"></i> Chia sẻ
                </button>
            </div>
        </div>
    </section>

    {{-- Phiếu: bố cục cố định 720px, thu nhỏ vừa màn hình bằng JS --}}
    <div class="cert-stage" id="cert-stage">
        <div class="cert-scale" id="cert-scale">
            <article class="certificate" id="certificate"
                data-file-name="{{ $fileName }}" data-share-url="{{ $shareUrl }}"
                data-share-title="Phiếu Quy Y - {{ $user->name }}">

                <div class="cert-frame" aria-hidden="true"></div>

                <div class="cert-qr" id="qr-svg-code" title="Quét mã để xem phiếu">
                    {!! $user->qr_code !!}
                    <span>Quét để xác thực</span>
                </div>

                <header class="cert-header">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="Logo Chùa Phước Lộc" class="cert-logo" width="92" height="88">
                    <p class="cert-org">Giáo Hội Phật Giáo Việt Nam</p>
                    <p class="cert-pagoda">Bổn Tự: Chùa Phước Lộc</p>
                    <div class="cert-divider">❖ ❁ ❖</div>
                    <h2 class="cert-title">Quy Y Tam Bảo</h2>
                </header>

                {{-- Thông tin Phật tử --}}
                <section class="cert-info {{ $user->avatar_url ? 'has-photo' : '' }}">
                    @if ($user->avatar_url)
                        <figure class="cert-photo">
                            <img src="{{ $user->avatar_url }}" alt="Ảnh {{ $user->name }}">
                        </figure>
                    @endif

                    <div class="cert-rows">
                        <div class="cert-row">
                            <span class="cert-label">Nay đệ tử:</span>
                            <span class="cert-value cert-name">{{ $user->name }}</span>
                        </div>
                        <div class="cert-row">
                            <span class="cert-label">Pháp danh:</span>
                            <span class="cert-value cert-dharma-name">{{ $user->nickname ?: '...' }}</span>
                        </div>
                        <div class="cert-row">
                            <span class="cert-label">Giới tính:</span>
                            <span class="cert-value cert-value-short">{{ $gender }}</span>
                            <span class="cert-label cert-label-inline">Ngày sinh:</span>
                            <span class="cert-value">{{ $birthDate }}</span>
                        </div>
                        <div class="cert-row cert-row-top">
                            <span class="cert-label">Địa chỉ:</span>
                            <span class="cert-value cert-value-multiline">{{ $fullAddress }}</span>
                        </div>
                    </div>
                </section>

                <p class="cert-pledge">
                    Xin nguyện trọn đời <strong>QUY Y TAM BẢO</strong>, thọ trì <strong>NĂM GIỚI CẤM</strong>
                    và <strong>BẢY ĐIỀU NGUYỆN</strong>.
                </p>

                {{-- Tam quy, Ngũ giới, Bảy điều nguyện --}}
                <section class="cert-vows">
                    <ol class="cert-refuges">
                        <li><strong>Quy y Phật</strong> - Đệ tử nguyện trọn đời tôn thờ Đấng Chánh Giác</li>
                        <li><strong>Quy y Pháp</strong> - Đệ tử nguyện trọn đời tôn thờ lời Phật dạy</li>
                        <li><strong>Quy y Tăng</strong> - Đệ tử nguyện trọn đời tôn thờ những vị xuất gia tu hành chân chính</li>
                    </ol>

                    <div class="cert-vows-grid">
                        <div class="cert-vows-box">
                            <h3>Năm Giới Cấm</h3>
                            <ol>
                                <li>Không sát sanh hại vật.</li>
                                <li>Không gian tham trộm cướp.</li>
                                <li>Không tà dâm.</li>
                                <li>Không nói bậy.</li>
                                <li>Không dùng chất say nghiện.</li>
                            </ol>
                        </div>
                        <div class="cert-vows-box">
                            <h3>Bảy Điều Nguyện</h3>
                            <ol>
                                <li>Cố gắng tập ăn chay.</li>
                                <li>Siêng năng học hỏi giáo pháp.</li>
                                <li>Thường xuyên toạ thiền, niệm Phật, lễ Phật.</li>
                                <li>Nỗ lực làm việc từ thiện.</li>
                                <li>Nguyện Phật hoá gia đình.</li>
                                <li>Nguyện giúp mọi người tin hiểu Phật Pháp.</li>
                                <li>Nguyện kiên cường hộ đạo.</li>
                            </ol>
                        </div>
                    </div>
                </section>

                {{-- Dùng div: client.css có style cho mọi thẻ footer --}}
                <div class="cert-footer">
                    <div class="cert-footer-left">
                        <p>Số điệp / Mã</p>
                        <p class="cert-code">{{ $user->uid_code ?: '...' }}</p>
                    </div>
                    <div class="cert-footer-right">
                        <p class="cert-date">
                            @if ($registered)
                                Ngày <b>{{ date('d', $registered) }}</b> tháng <b>{{ date('m', $registered) }}</b> năm <b>{{ date('Y', $registered) }}</b>
                            @else
                                Ngày ..... tháng ..... năm .........
                            @endif
                        </p>
                        <p class="cert-master-title">Bổn sư truyền thọ</p>
                        <img src="{{ asset('images/stamp.png') }}" alt="Con dấu" class="cert-stamp" width="230" height="109">
                        <p class="cert-master-name">ĐĐ. Thích Tánh Minh</p>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <p class="pl-theme cert-tip">
        <i class="bi bi-lightbulb"></i> Lưu mã QR hoặc đường dẫn này để tra cứu phiếu bất cứ lúc nào.
    </p>
</div>

@include('client.layouts.menu-bar')
@endsection

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Imperial+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/certificate.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/certificate.js') }}"></script>
@endpush
