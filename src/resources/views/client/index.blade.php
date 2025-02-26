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
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-6">
                    <ul class="lists-btn">
                        <li><img src="{{asset('/images/icon_banhxephap2.png')}}" alt=""><a href="{{route('client.quyy.create')}}">Đăng Ký Quy Y</a></li>
                        <li><img src="{{asset('/images/icon_banhxephap2.png')}}" alt=""><a href="#">Đăng Ký Cầu An</a></li>
                        <li><img src="{{asset('/images/icon_banhxephap2.png')}}" alt=""><a href="#">Đăng Ký Cầu Siêu</a></li>
                    </ul>
                    <div class="btn-footer">
                        <a href="https://chuaphuocloc.com/"><button>Trang chủ</button></a>
                        <a href="{{route('admin.login')}}"><button>Đăng nhập Quản Lý</button></a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection