@extends('admin.layouts.main')
@section('title', 'DS Chờ Duyệt')
@section('content')

    <div class="form-import card">
        <div class="card-body">
            <form action="{{ route('admin.quyy.import') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="" class="form-label">Vui lòng chọn file có định dạng .csv</label>
                        <input class="form-control" type="file" name="file" id="" accept=".csv">
                        @error('file')
                            <div style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Nhập</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection