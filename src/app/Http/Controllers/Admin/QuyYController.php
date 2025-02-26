<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TemporaryUserService\ListTemporaryUserServicesService;

class QuyYController extends Controller
{
    public function index(){
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Danh Sách Quy Y', 'url' => null]
        ];
        return view('admin.quyy.index', compact('breadcrumbs'));
    }

    public function list(ListTemporaryUserServicesService $listTemporaryUserServicesService){
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Danh Sách Quy Y', 'url' => null]
        ];
        $lists = $listTemporaryUserServicesService->paginate();
        return view('admin.quyy.list', compact('lists', 'breadcrumbs'));
    }
    public function create(){
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Thêm Phật Tử', 'url' => null]
        ];
        return view('admin.quyy.create', ['breadcrumbs' => $breadcrumbs]);
    }
}
