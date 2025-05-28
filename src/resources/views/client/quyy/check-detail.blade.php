@extends('client.layouts.main')

@section('content')
    <div class="container search-home">
        <h1 class="s-text-header text-center">Nhập số điện thoại của phái quy y</h1>
        <div class="detail-container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" required>
                                </div>
                                <div class="form-group">
                                    <label for="check-bot">Kiem tra bot</label>
                                    <input type="text" class="form-control" id="check-bot" name="check-bot" placeholder="Kiem tra bot" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Kiểm tra</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('client.layouts.menu-bar')