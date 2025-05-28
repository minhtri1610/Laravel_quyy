@extends('admin.layouts.main')

@section('content')
   <div class="row">
       <div class="col-12">
            <a href="{{ route('admin.quyy.import') }}">
            <button class="btn btn-warning">Nhập hàng loạt</button>
            </a>
       </div>
   </div>
   <div class="wp-add mt-3">

   </div>
@endsection
