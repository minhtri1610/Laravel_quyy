<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService\ListUserServicesService;
use App\Services\TemporaryUserService\ListTemporaryUserServicesService;
use DB;

class DashboardController extends Controller
{
    public function index(
        ListUserServicesService $listUserService,
        ListTemporaryUserServicesService $listTemporaryUserService,
        Request $request,
    ){
        //get data dashboard
        $conditions  = [
            'approved' => 0,
            'orders'   => [
                'id' => 'desc',
            ],
        ];
        $lists = $listUserService->list($conditions);
        $lists_temp = $listTemporaryUserService->list($conditions);
        $years = DB::table('users')->select(DB::raw('YEAR(date_registered) as year'), DB::raw('COUNT(*) as total'))->groupBy('year')->get();

        $data = [
            'total_users' => $lists->where('is_active', 1)->count(),
            'total_users_pending' => $lists_temp->count(),
            'per_year' => $years,
        ];
        return view('admin.dashboard', compact('data'));
    }
}
