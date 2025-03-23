@extends('client.layouts.main')

@section('content')
    <!-- Video nền -->
    <video autoplay muted loop playsinline class="video-background">
        <source src="{{asset('/images/bg_video.mp4')}}" type="video/mp4">
    </video>

    <!-- Lớp phủ -->
    <div class="overlay"></div> 
    <h1 class="pg-title">CHÙA PHƯỚC LỘC</h1>
    <div class="wapper-home">
        <div class="container-lotus">
            <div class="button-container">
                <a class="button" href="{{ route('client.quyy.create') }}">
                    <i class="bi bi-award"></i>
                    <span class="text">ĐK Quy Y</span>
                </a>
                <button class="button">
                    <i class="bi bi-clipboard-heart"></i>
                    <span class="text">ĐK Cầu An</span>
                </button>
                <button class="button">
                    <i class="bi bi-person-video"></i>
                    <span class="text">ĐK Cầu Siêu</span>
                </button>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        <ul class="list-unstyled d-flex gap-4">
            <li><a href="https://chuaphuocloc.com/" class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i class="bi bi-bank2"></i></a></li>
            <li><a href="{{ route('client.quyy.search') }}" class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i class="bi bi-search"></i></a></li>
            <li><a href="{{ route('admin.login') }}" class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i class="bi bi-gear"></i></a></li>
        </ul>
    </div>

@endsection

@push('css')
    <style>
        .pg-btn{
            position: relative;
            z-index: 99;
            color: #fff;
            border: 1px solid #fff;
        }

        .pg-btn:hover{
            color: #FFEB3B;
            border: 1px solid #FFEB3B;
        }

        .pg-title {
            font-size: 48px;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(45deg, #FFD700, #FF8C00, #DAA520);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            text-shadow: 2px 2px 10px rgba(255, 215, 0, 0.8);
            
            position: absolute;
            top: 3%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        .wapper-home{
            margin: 0;
            padding: 0;
            background: url('background-image.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
        }
        .container-lotus {
            position: relative;
            width: 500px;
            height: 500px;
        }
        .lotus {
            width: 100%;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .button-container {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid rgba(255, 215, 0, 0.7);
            border-radius: 50%;
        }
        .container-lotus .button i {
            font-size: 24px;
            transition: margin-right 0.3s ease-in-out;
        }
        .container-lotus .button .text {
            opacity: 0;
            width: 0;
            transition: opacity 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        .container-lotus .button:hover, .container-lotus .button:focus {
            width: 180px;
            height: 60px;
            border-radius: 30px;
            box-shadow: 0 0 20px rgba(255, 215, 0, 1);
            justify-content: flex-start;
            padding-left: 20px;
            outline: none;
        }
        .container-lotus .button:hover .text, .container-lotus .button:focus .text {
            opacity: 1;
            width: auto;
            margin-left: 10px;
        }

        /* Animation quay vòng */
        @keyframes orbit {
            0% { transform: rotate(0deg) translateX(150px) rotate(0deg); }
            100% { transform: rotate(360deg) translateX(150px) rotate(-360deg); }
        }
        .container-lotus .button:nth-child(1) { animation-delay: 0s; }
        .container-lotus .button:nth-child(2) { animation-delay: 1.67s; }
        .container-lotus .button:nth-child(3) { animation-delay: 3.33s; }
        
        /* Animation for circular motion */
        @keyframes orbit {
            0% { transform: rotate(0deg) translateX(250px) rotate(0deg); }
            100% { transform: rotate(360deg) translateX(250px) rotate(-360deg); }
        }
        .container-lotus .button:nth-child(1) { animation-delay: 0s; }
        .container-lotus .button:nth-child(2) { animation-delay: 3.33s; }
        .container-lotus .button:nth-child(3) { animation-delay: 6.67s; }

        .container-lotus .button {
            position: absolute;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle at 30% 30%, #FFC107, #8B5E3C);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4), 
                        -4px -4px 10px rgba(255, 215, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            padding: 10px;
            animation: orbit 10s linear infinite;
            white-space: nowrap;
        }

    /* Hover: Thêm hiệu ứng sáng hơn */
    .container-lotus .button:hover, .container-lotus  .button:focus {
        background: radial-gradient(circle at 70% 30%, #FFD700, #8B5E3C);
        box-shadow: 6px 6px 15px rgba(0, 0, 0, 0.5), 
                    -6px -6px 15px rgba(255, 215, 0, 0.8);
        transform: scale(1.1);
    }

    /* Icon hiển thị mặc định */
    .container-lotus .button i {
        font-size: 24px;
        transition: margin-right 0.3s ease-in-out;
    }

    /* Hover hiển thị chữ */
    .container-lotus .button .text {
        opacity: 0;
        width: 0;
        transition: opacity 0.3s ease-in-out, width 0.3s ease-in-out;
    }

    .container-lotus .button:hover .text, .container-lotus  .button:focus .text {
        opacity: 1;
        width: auto;
        margin-left: 10px;
    }


    </style>
@endpush

@push('scripts')
    <script>
        const buttons = document.querySelectorAll('.button');

        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                buttons.forEach(btn => btn.style.animationPlayState = 'paused');
            });

            button.addEventListener('mouseleave', () => {
                buttons.forEach(btn => btn.style.animationPlayState = 'running');
            });
        });
    </script>
@endpush