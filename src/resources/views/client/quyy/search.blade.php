@extends('client.layouts.main')

@section('content') 
    <div class="container search-home">
        <h1 class="s-text-header text-center">Tìm Kiếm Phái Quy Y</h1>
        <div class="search-container mt-4">
            <form action="" method="get">
                <input type="text" name="key-word" value="{{ request()->get('key-word') }}" class="form-control" placeholder="Tìm kiếm theo tên, pháp danh, sdt...">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="search-result">
            <div class="row">
                @if (!empty($users))
                    @foreach ($users as $user)
                        <div class="col-md-12 mb-3">
                            <div class="card-item">
                                <div class="card-body-item">
                                    <div class="c-left">
                                        <img src="{{asset('/images/icon_banhxephap.png')}}" alt="" srcset="">
                                    </div>
                                    <div class="c-right">
                                        <p class="card-text mb-0">Họ và tên: <b>{{ $user->name }}</b></p>
                                        <p class="card-text mb-0">Pháp danh: <b>{{ $user->nickname }}</b></p>
                                        <p class="card-text mb-0">Số ĐT: <b>{{ $user->phone ? format_phone($user->phone) : '-' }}</b> | Email: <b>{{ $user->email ? formatHiddenEmail($user->email) : '-' }}</b></p>
                                    </div>
                                    <div class="c-action">
                                    <!-- href="{{ route('client.quyy.detail', ['uid' => $user->uid]) }}" -->
                                    <!-- thêm phần nhập sdt vào -->
                                        <a  class="btn btn-primary" onclick="getInfoCertificate(this)" data-uid="{{ $user->uid }}" data-nickname="{{ $user->nickname }}" data-name="{{ $user->name }}" data-toggle="modal" data-target="#modal-verify-search">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Vui lòng nhập thông tin tìm kiếm ...</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@include('client.layouts.menu-bar')
@include('client.quyy._modal-verify-search')

@push('scripts')
    <script>
        function getInfoCertificate(e) {
            let uid = $(e).data('uid');
            let full_name = $(e).data('name');
            let nickname = $(e).data('nickname');
            $('#m_uid').val(uid);
            $('#m_name').val(full_name);
            $('#m_nickname').val(nickname);
            $('#modal-verify-search').modal('show');
        }

        function getInfo(){
            let data = {
                'uid': $('#m_uid').val(),
                'phone': $('#m_phone').val(),
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
            return data;
        }

        function verifyPhone() {
            if($('#m_phone').val() == '') {
                toastr.error("Vui lòng nhập số điện thoại!", "Thông báo");
                return false;
            }
            let data = getInfo();
            let url = $('#form-verify').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function (result) {
                    if(result.status == true){
                        window.location.href = result.url;
                    } else {
                        toastr.error(result.message, "Thông báo");
                    }
                }
            });
        }


    </script>
@endpush