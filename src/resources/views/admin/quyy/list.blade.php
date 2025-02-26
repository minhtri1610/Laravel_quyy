@extends('admin.layouts.main')
@section('title', 'DS Chờ Duyệt')
@section('content')
    <!-- Dark table start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Danh Sách Chờ Duyệt Quy Y</h4>
                    <div class="data-tables datatable-dark">
                        <table id="dataTable3" class="text-center">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và Tên</th>
                                    <th>Ngày Đăng Ký</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao tác</th>
                                    <th>Liên Hệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lists as $key => $item)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$item->full_name}}</td>
                                    <td>{{date('Y-m-d', strtotime($item->created_at))}}</td>
                                    <td>@if($item->appproved == 0) Chưa duyệt @else Đã duyệt @endif</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Duyệt</button>
                                        <button class="btn btn-sm btn-danger">Xóa</button>
                                    </td>
                                    <td class="text-left">
                                        <br>
                                        SĐT: {{$item->phone_number}} <br>
                                        Email: {{$item->email}}<br>
                                        Địa chỉ: {{$item->province}} - {{$item->district}} - {{$item->ward}} - {{$item->address}} <br>
                                    </td>
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
            <!-- Phân trang của Laravel -->
            
        </div>
    </div>
    <nav class="flex items-center justify-between">
    {{ $lists->links() }}
    </nav>
    <!-- Dark table end -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#quyyTable').DataTable({
                processing: true,
                serverSide: true, // Bật server-side processing
                ajax: {
                    url: "{{ route('admin.quyy.list') }}", // URL lấy dữ liệu
                    type: "GET",
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'quyy_date', name: 'quyy_date' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return '<a href="#">Sửa</a> | <a href="#">Xóa</a>';
                        }
                    }
                ],
                pageLength: 5, // Số bản ghi mỗi trang
                lengthMenu: [5, 10, 25, 50], // Tùy chọn số bản ghi
                paging: false
            });
        });
    </script>
@endpush
