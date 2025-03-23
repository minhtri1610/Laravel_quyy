@extends('admin.layouts.main')
@section('title', 'DS Quy Y')
@section('content')
    <!-- Dark table start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="header-title">Data Table Dark</h4> -->
                    <div class="row">
                        <div class="col-6">
                            <form class="d-flex" role="search">
                                <input class="form-control me-2" type="search" placeholder="Tìm tên, pháp danh, sdt..." aria-label="Search">
                                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                            </form>
                        </div>
                    </div>
                    
                    <table id="" data-toggle="table" class="table table-bordered mt-3">
                        <thead class="text-capitalize table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pháp danh</th>
                                <th>Thông tin</th>
                                <th>Liên Hệ</th>
                                <th>Ngày Đăng Ký</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lists as $key => $item)
                            <tr>
                                <td> <b> {{$item->uid_code}} </b> </td>
                                <td>{{$item->nickname}}</td>
                                <td>
                                    <b>Tên:</b> {{$item->name}} </br>
                                    <b>Giới tính:</b> {{$item->gender == 'male' ? 'Nam' : 'Nữ'}} </br>
                                </td>
                                <td>
                                    <b>SĐT:</b> {{$item->phone}} </br>
                                    <b>Email:</b> {{$item->email}} </br>
                                    <b>Địa chỉ:</b>  {{$item->province}} - {{$item->district}} - {{$item->ward}} - {{$item->address}}  </br>
                                </td>
                                <td>{{$item->date_registered}}</td>
                                <td></td>
                            </tr>
                            @empty
                            <tr>
                                Danh Sách trống
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Dark table end -->
@endsection
