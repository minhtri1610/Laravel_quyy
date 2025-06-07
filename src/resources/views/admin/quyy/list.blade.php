@extends('admin.layouts.main')
@section('title', 'DS Chờ Duyệt')
@section('content')
    <!-- Dark table start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Danh Sách Chờ Duyệt Quy Y</h4>
                    <!-- <div class=" datatable-dark"> -->
                        <table id="" class="text-center"  data-toggle="table">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>ID</th>
                                    <th>Họ và Tên</th>
                                    <th>Liên Hệ</th>
                                    <th>Ngày Đăng Ký</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lists as $key => $item)
                                <tr>
                                    <td data-id="{{$item->id}}">#{{ $item->id }}</td>
                                    <td data-full-name="{{ $item->full_name }}">{{$item->full_name}}</td>
                                    <td class="text-left" data-phone="{{$item->phone_number}}"  data-email="{{$item->email}}" data-province="{{$item->province}}" data-district="{{$item->district}}" data-ward="{{$item->ward}}" data-address="{{$item->address}}"
                                    data-gender="{{$item->gender}}" data-birthday="{{$item->birth_date}}" data-nick-name="{{$item->nickname}}">
                                        <b>SĐT:</b> {{$item->phone_number}} <br>
                                        <b>Email:</b> {{$item->email}}<br>
                                        <b>Địa chỉ:</b> {{$item->province}} - {{$item->district}} - {{$item->ward}} - {{$item->address}} <br>
                                    </td>
                                    <td data-created-at="{{ $item->created_at }}">{{date('Y-m-d', strtotime($item->created_at))}}</td>
                                    <td>
                                        @if($item->approved == 0)
                                        <button class="btn btn-outline-danger" disabled>
                                            Chưa duyệt
                                        </button>
                                        @else
                                        <button class="btn btn-outline-success"  disabled>
                                            Đã duyệt 
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary w-100" onclick="getInfo(this)" data-toggle="modal" data-target="#modal-verify">Duyệt</button>
                                        <button class="btn btn-sm btn-danger mt-1 w-100" onclick="showConfirm({{$item->id}})"  data-toggle="modal" data-target="#modal-confirm">Xóa</button>
                                    </td>
                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        Danh Sách trống
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    <!-- </div> -->
                </div>
            </div>
            <!-- Phân trang của Laravel -->
            
        </div>
    </div>
    <div class="mt-4">
        {{ $lists->links('admin.layouts.pagination') }}
    </div>

@endsection
@include('admin.quyy._modal-verify')
@include('admin.commons._md-confirm',['action' => route('admin.quyy.delete'), 'title' => 'Xác nhận', 'des' => 'Xóa khỏi danh sách chờ duyệt?'])
@push('scripts')
    <script src="{{ asset('js/suggest-name.js') }}"></script>
    <script>
        
        function getInfo(event){
            let data = {};
            let row = $(event).closest('tr');
            row.find("td").each(function () {
                $.each(this.dataset, function (key, value) {
                    data[key] = value;
                });
            });
            console.log(data);
            fillModal(data);
        }

        function resetData(){
            $('#modal-verify #m_id').val('');
            $('#modal-verify #m_fullname').val('');
            $('#modal-verify #m_gender').html('');
            $('#modal-verify #m_phone').html('');
            $('#modal-verify #m_email').html('');
            $('#modal-verify #m_address').html('');
            $('#modal-verify #m_nick_name').val('');
            $('#modal-verify #uid_code').val('');
            
        }

        function fillModal(data){
            resetData();
            $('#modal-verify #m_id').val(data.id);
            $('#modal-verify #m_fullname').val(data.fullName);
            let gender = data.gender == 'male' ? 'Nam' : 'Nữ';
            $('#modal-verify #m_gender').html(gender);
            $('#modal-verify #m_phone').html(data.phone);
            $('#modal-verify #m_email').html(data.email);
            let address = data.province + ' - ' + data.district + ' - ' + data.ward + ' - ' + data.address;
            if(data.province == ''){
                address = '';
            }
            $('#modal-verify #m_address').html(address);
            $('#modal-verify #m_birthday').html(data.birthday);
            $('#modal-verify #m_nick_name').val(data.nickName);
        }

        function verify(){
            let id = $('#modal-verify #m_id').val();
            let nick_name = $('#modal-verify #m_nick_name').val();
            if(nick_name == '') {
                toastr.error("Vui lòng nhập Pháp Danh!", "Thông báo");
                return false;
            };
            let data = {
                'nick_name': nick_name ,
                '_token': $('meta[name="csrf-token"]').attr('content')
            }
            let url = $('#form-verify').attr('action') + '/' + id;
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function (result) {
                    if(result.success == true){
                        toastr.success(result.message, "Thành công");
                        location.reload();
                    } else{
                        toastr.error(result.message, "Lỗi");
                    }
                }
            });
        }

        function showConfirm(id){
            $('#modal-confirm #m_cf_id').val(id);
            $('#modal-confirm').modal('show');
        }

        function confirmDelete(){
            let id = $('#modal-confirm #m_cf_id').val();
            let url = $('#form-confirm').attr('action') + '/' + id;
            $('#form-confirm').attr('action', url);
            $('#form-confirm').submit();
        }

    </script>
@endpush
