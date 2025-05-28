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
                            <img src="{{asset('/images/icon_banhxephap.png')}}" alt="">
                            <a class="cli-top-link" href="{{route('client.quyy.create')}}">Đăng Ký Quy Y</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                    </div>
                    <div class="carousel-item">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouseTopPage" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouseTopPage" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>

            <div class="d-flex justify-content-center align-items-center cli-top-btn">
                <ul class="list-unstyled d-flex gap-4">
                    <li><a href="https://chuaphuocloc.com/" class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i class="bi bi-bank2"></i></a></li>
                    <li><a href="{{ route('client.quyy.search') }}" class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i class="bi bi-search"></i></a></li>
                    <li><a href="{{ route('admin.login') }}" class="btn pg-btn btn-lg rounded-circle d-flex justify-content-center align-items-center shadow"><i class="bi bi-gear"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/sakura.css') }}" type="text.css">
    <style>
        .wapper-home{
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
        .cli-top-btn{

        }
        .wapper-carousel{
            height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-flow: column;
        }

        .wapper-carousel img{
            margin-bottom: 30px;
            width: 150px;
            animation: spin 3s linear infinite; 
        }
        

        .carousel-item .cli-top-link{
            list-style: none;
            
        }
        .carousel-item .cli-top-link img{
            width: 45px;
            height: 45px;
        }
        .carousel-control-next-icon, .carousel-control-prev-icon{
            background-color: #c23616;
        }

        .cli-top-link{
            text-decoration: none;
            padding: 15px 30px;
            border: 1px solid yellow;
            color: yellow;
            background: linear-gradient(#962a0e, #d46247, #ffe698);
            border-radius: 50px;
        }
        .cli-top-link a{
            color: yellow;
            text-decoration: none;
        }
        body{
            display: block;
        }
    </style>
@endpush