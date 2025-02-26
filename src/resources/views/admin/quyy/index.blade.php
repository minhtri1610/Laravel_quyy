@extends('admin.layouts.main')
@section('title', 'DS Quy Y')
@section('content')
    <!-- Dark table start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Data Table Dark</h4>
                    <div class="data-tables datatable-dark">
                        <table id="dataTable3" class="text-center">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và Tên</th>
                                    <th>Liên Hệ</th>
                                    <th>Ngày Đăng Ký</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lists as $key => $item)
                                <tr>
                                    <td>{{$key + 1}}/td>
                                    <td>{{$item->full_name}}</td>
                                    <td>SĐT: {{$item->phone_number}}</td>
                                    <td>{{}}</td>
                                    <td></td>
                                    <td>$162,700</td>
                                </tr>
                                @empty
                                <tr>
                                    No data
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dark table end -->
@endsection
