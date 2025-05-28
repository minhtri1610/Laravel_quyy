@extends('client.layouts.main')

@push('css')
<style>
    .certificate {
        position: relative;
        border: 3px solid #FFEB3B;
        padding: 40px;
        /* border-radius: 20px; */
        width: 600px;
        margin: 10vh auto;
        box-shadow: 5px 8px 14px #795548;
        background: linear-gradient(180deg, yellow, #ffc107);
    }

    .certificate h1 {
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        margin: 0;
        color: red;
        text-shadow: 2px 1px #FFEB3B;
    }

    .certificate h3 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: bold;
        color: #795548;
        text-shadow: 2px 1px #FFEB3B;
        text-transform: uppercase;
    }

    .certificate .logo {
        position: absolute;
        top: 15px;
        left: 15px;
        width: 100px;
        height: 100px;
    }

    .certificate .logo img {
        max-width: 100%;
    }

    .details {
        text-align: left;
        font-size: 18px;
        line-height: 1.8;
        margin-top: 20px;
    }

    .label {
        font-weight: bold;
    }

    .footer {
        margin-top: 40px;
        text-align: right;
        font-size: 16px;
    }

    .qr_code{
        position: absolute;
        right: 20px;
        top: 20px;
    }
    .qr_code svg{
        width: 80px;
        height: 80px;
    }

    .details .d-name{
        font-family: "Imperial Script", cursive;
        font-weight: 400;
        font-style: normal;
        font-size: 48px;
        line-height: 0.5;
    }
    .details .label{
        margin-bottom: 0;
    }
    .details p, .footer p{
        margin-bottom: 0;
    }

    .icon-img img{
        width: 120px;
        position: absolute;
        bottom: -10px;
        left: 40%;
    }
</style>
@endpush
@section('content')
<div class="container search-home">
    <!-- <h1 class="text-center">Chi Tiết Phái Quy Y</h1>
        <div class="detail-container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{$user->name}}</h5>
                            <p class="card-text">Pháp danh: {{$user->nickname}}</p>
                            <p class="card-text">Giới tính: {{$user->gender}}</p>
                            <p class="card-text">Ngày sinh: {{$user->birth_date}}</p>
                            <p class="card-text">Sdt: {{$user->phone}}</p>
                            <p class="card-text">Email: {{$user->email}}</p>
                            <p class="card-text">Địa chỉ: {{$user->country}} - {{$user->city}} -  {{$user->state}} -  {{$user->address}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

    <div class="certificate">
        <h1>THẺ QUY Y</h1>
        <h3>CHÙA PHƯỚC LỘC</h3>
        <div class="logo">
            <img src="{{asset('/images/logo-sm.png')}}" alt="">
        </div>
        <div class="qr_code">
            {!! $user->qr_code !!}
        </div>
        <div class="details">
            <p><span class="label">Họ tên:</span> <span class="label d-name">{{$user->name}}</span></p>
            <p><span class="label">Pháp danh:</span> <span class="label d-name"> {{$user->nickname ?? '...'}}</span></p>
            <p><span class="label">Giới tính:</span> {{$user->gender == 'male' ? 'Nam' : 'Nữ'}} | <span class="label">Ngày sinh:</span> {{$user->birth_date}}</p>
            @if(empty($user->country))
            <p><span class="label">Địa chỉ:</span> ... </p>
            @else
            <p><span class="label">Địa chỉ:</span> {{$user->country}} - {{$user->city}} -  {{$user->state}} -  {{$user->address}}</p>
            @endif
        </div>

        <div class="icon-img">
            <img src="{{asset('/images/lotus-icon.png')}}" alt="">
        </div>

        <div class="footer">
            <p>Ngày cấp: {{ date('d-m-Y', strtotime($user->date_registered))}}</p>
            <p>Mã số phái: <span class="label">{{$user->uid_code}}</span></p>
        </div>
    </div>
</div>
@endsection

@include('client.layouts.menu-bar')