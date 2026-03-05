@extends('client.layouts.main')

@section('content')
    <div class="wapper-home">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-home">
                        <h1 class="text-center text-header my-5">Chùa Phước Lộc</h1>
                    </div>
                </div>
            </div>

            <div id="carouseTopPage" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="wapper-carousel">
                            <img src="{{asset('/images/icon_banhxephap.png')}}" alt="Pháp Luân">

                            <!-- Search Form directly on home -->
                            <form action="{{ route('client.quyy.search') }}" method="GET" class="w-100 mt-4 mb-5">
                                <div class="mx-auto" style="max-width: 650px; width: 95%;">
                                    <div class="input-group p-1"
                                        style="background-color: rgba(255,255,255,0.95); border-radius: 50px; border: 2px solid #ffeb3b; box-shadow: 0 5px 15px rgba(0,0,0,0.15);">
                                        <input type="text" name="key-word" placeholder="Nhập pháp danh, họ tên, SĐT..."
                                            class="form-control border-0 shadow-none bg-transparent" required
                                            style="padding: 12px 20px; font-size: 16px; outline: none;">
                                        <button type="submit"
                                            class="btn d-flex align-items-center justify-content-center m-0"
                                            style="background: linear-gradient(#d83c17, #9a2b11); color: #fff; font-size: 16px; font-weight: 600; border-radius: 40px; padding: 10px 24px; box-shadow: 0 4px 8px rgba(154, 43, 17, 0.3); z-index: 10; border: none;">
                                            <i class="bi bi-search"></i> <span class="d-none d-sm-inline ms-2">Tìm
                                                kiếm</span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <a class="cli-top-link mt-4" href="{{route('client.quyy.create')}}">Đăng Ký Quy Y</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center cli-top-btn">
                <ul class="list-unstyled d-flex gap-4">
                    <li><a href="https://chuaphuocloc.com/"
                            class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i
                                class="bi bi-house-door-fill"></i></a></li>
                    <li><a href="#" id="toggle-music"
                            class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i
                                class="bi bi-volume-up-fill" id="music-icon"></i></a></li>
                    <li><a href="{{ route('admin.login') }}"
                            class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i
                                class="bi bi-gear"></i></a></li>
                </ul>
                <audio id="bg-music" loop style="display:none;">
                    <source src="{{ asset('mp3/m01.mp3') }}" type="audio/mpeg">
                </audio>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/sakura.css') }}" type="text.css">
    <style>
        .sakura { pointer-events: none; position: fixed !important; z-index: 9999; } @-webkit-keyframes fall { 0% { opacity: 0.9; top: -10vh; } 100% { opacity: 0.2; top: 100vh; } } @keyframes fall { 0% { opacity: 0.9; top: -10vh; } 100% { opacity: 0.2; top: 100vh; } } @-webkit-keyframes blow-soft-left { 0% { margin-left: 0; } 100% { margin-left: -50vw; } } @keyframes blow-soft-left { 0% { margin-left: 0; } 100% { margin-left: -50vw; } } @-webkit-keyframes blow-medium-left { 0% { margin-left: 0; } 100% { margin-left: -100vw; } } @keyframes blow-medium-left { 0% { margin-left: 0; } 100% { margin-left: -100vw; } } @-webkit-keyframes blow-soft-right { 0% { margin-left: 0; } 100% { margin-left: 50vw; } } @keyframes blow-soft-right { 0% { margin-left: 0; } 100% { margin-left: 50vw; } } @-webkit-keyframes blow-medium-right { 0% { margin-left: 0; } 100% { margin-left: 100vw; } } @keyframes blow-medium-right { 0% { margin-left: 0; } 100% { margin-left: 100vw; } } @-webkit-keyframes sway-0 { 0% { transform: rotate(-5deg); } 40% { transform: rotate(28deg); } 100% { transform: rotate(3deg); } } @keyframes sway-0 { 0% { transform: rotate(-5deg); } 40% { transform: rotate(28deg); } 100% { transform: rotate(3deg); } } @-webkit-keyframes sway-1 { 0% { transform: rotate(10deg); } 40% { transform: rotate(43deg); } 100% { transform: rotate(15deg); } } @keyframes sway-1 { 0% { transform: rotate(10deg); } 40% { transform: rotate(43deg); } 100% { transform: rotate(15deg); } } @-webkit-keyframes sway-2 { 0% { transform: rotate(15deg); } 40% { transform: rotate(56deg); } 100% { transform: rotate(22deg); } } @keyframes sway-2 { 0% { transform: rotate(15deg); } 40% { transform: rotate(56deg); } 100% { transform: rotate(22deg); } } @-webkit-keyframes sway-3 { 0% { transform: rotate(25deg); } 40% { transform: rotate(74deg); } 100% { transform: rotate(37deg); } } @keyframes sway-3 { 0% { transform: rotate(25deg); } 40% { transform: rotate(74deg); } 100% { transform: rotate(37deg); } } @-webkit-keyframes sway-4 { 0% { transform: rotate(40deg); } 40% { transform: rotate(68deg); } 100% { transform: rotate(25deg); } } @keyframes sway-4 { 0% { transform: rotate(40deg); } 40% { transform: rotate(68deg); } 100% { transform: rotate(25deg); } } @-webkit-keyframes sway-5 { 0% { transform: rotate(50deg); } 40% { transform: rotate(78deg); } 100% { transform: rotate(40deg); } } @keyframes sway-5 { 0% { transform: rotate(50deg); } 40% { transform: rotate(78deg); } 100% { transform: rotate(40deg); } } @-webkit-keyframes sway-6 { 0% { transform: rotate(65deg); } 40% { transform: rotate(92deg); } 100% { transform: rotate(58deg); } } @keyframes sway-6 { 0% { transform: rotate(65deg); } 40% { transform: rotate(92deg); } 100% { transform: rotate(58deg); } } @-webkit-keyframes sway-7 { 0% { transform: rotate(72deg); } 40% { transform: rotate(118deg); } 100% { transform: rotate(68deg); } } @keyframes sway-7 { 0% { transform: rotate(72deg); } 40% { transform: rotate(118deg); } 100% { transform: rotate(68deg); } } @-webkit-keyframes sway-8 { 0% { transform: rotate(94deg); } 40% { transform: rotate(136deg); } 100% { transform: rotate(82deg); } } @keyframes sway-8 { 0% { transform: rotate(94deg); } 40% { transform: rotate(136deg); } 100% { transform: rotate(82deg); } } .wapper-home {
            background: url('images/bg-v2.png') no-repeat center center/cover;
        }

        .pg-btn {
            margin-bottom: 5px;
            font-size: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 65px;
            height: 65px;
            text-align: center;
            border: 1px solid #ffeb3b;
            color: #ffff00;
            background: linear-gradient(#962a0e, #d46247, #ffe698);
        }

        .pg-btn:hover {
            color: #962a0e;
            background: #ffe698;
            text-decoration: none;

        }

        .cli-top-btn {}

        .wapper-carousel {
            height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-flow: column;
        }

        .wapper-carousel img {
            margin-bottom: 30px;
            width: 150px;
            animation: spin 3s linear infinite;
        }


        .carousel-item .cli-top-link {
            list-style: none;

        }

        .carousel-item .cli-top-link img {
            width: 45px;
            height: 45px;
        }

        .carousel-control-next-icon,
        .carousel-control-prev-icon {
            background-color: #c23616;
        }

        .cli-top-link {
            text-decoration: none;
            padding: 15px 30px;
            border: 1px solid yellow;
            color: yellow;
            background: linear-gradient(#962a0e, #d46247, #ffe698);
            border-radius: 50px;
        }

        .cli-top-link a {
            color: yellow;
            text-decoration: none;
        }

        body {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/animations/sakura.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Khởi tạo hiệu ứng hoa rơi (Sakura plugin từ sakura.js)
            var sakura = new Sakura('body', {
                fallSpeed: 1,
                maxSize: 14,
                minSize: 10,
                delay: 250
            });

            // Xử lý bật/tắt nhạc thiền
            var bgMusic = document.getElementById("bg-music");
            var toggleBtn = document.getElementById("toggle-music");
            var musicIcon = document.getElementById("music-icon");
            var isPlaying = false;

            // Thử tự động phát khi trang tải (nhiều trình duyệt sẽ chặn autoplay cho tới khi người dùng tương tác)
            var playPromise = bgMusic.play();
            if (playPromise !== undefined) {
                playPromise.then(_ => {
                    isPlaying = true;
                    musicIcon.classList.remove('bi-volume-mute-fill');
                    musicIcon.classList.add('bi-volume-up-fill');
                }).catch(error => {
                    // Trình duyệt chặn autoplay do chính sách (phải đợi người dùng click)
                    isPlaying = false;
                    musicIcon.classList.remove('bi-volume-up-fill');
                    musicIcon.classList.add('bi-volume-mute-fill');
                });
            }

            toggleBtn.addEventListener("click", function (e) {
                e.preventDefault();
                if (isPlaying) {
                    bgMusic.pause();
                    musicIcon.classList.remove('bi-volume-up-fill');
                    musicIcon.classList.add('bi-volume-mute-fill');
                    isPlaying = false;
                } else {
                    bgMusic.play();
                    musicIcon.classList.remove('bi-volume-mute-fill');
                    musicIcon.classList.add('bi-volume-up-fill');
                    isPlaying = true;
                }
            });
        });
    </script>
@endpush