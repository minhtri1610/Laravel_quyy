<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TemporaryUserService\ListTemporaryUserServicesService;
use App\Services\UserService\CreateUserServiceService;
use App\Services\UserService\ListUserServicesService;
use Illuminate\Database\Eloquent\Casts\Json;

class QuyYController extends Controller
{
    public function index(
        ListUserServicesService $listUserServicesService
    )
    {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Danh Sách Quy Y', 'url' => null]
        ];
        $lists = $listUserServicesService->paginate();
        return view('admin.quyy.index', compact('breadcrumbs', 'lists'));
    }

    public function list(ListTemporaryUserServicesService $listTemporaryUserServicesService)
    {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Danh Sách Quy Y', 'url' => null]
        ];
        $conditions = [
            "raw_select_columns" => "temporary_users.*, users.nickname",
            'orders' => [
                'id' => 'desc',
            ],
            'join_tables' => [
                [
                    "type"   => "LEFT",
                    "table"  => "users",
                    "second" => "users.id",
                    "first"  => "temporary_users.temporary_user_id"
                ]

            ],
            'approved' => 0,
        ];
        $lists = $listTemporaryUserServicesService->paginate($conditions);
        return view('admin.quyy.list', compact('lists', 'breadcrumbs'));
    }
    public function create()
    {
        $breadcrumbs = [
            ['title' => 'QL Quy Y', 'url' => route('admin.quyy.index')],
            ['title' => 'Thêm Phật Tử', 'url' => null]
        ];
        return view('admin.quyy.create', ['breadcrumbs' => $breadcrumbs]);
    }

    public function verify(
        Request $request,
        ListUserServicesService $listUserServicesService,
        ListTemporaryUserServicesService $listTemporaryUserServicesService,
        CreateUserServiceService $createUserServiceService,
        $id
    ) {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID'
                ], 400);
            }
            $data = $request->all();
            $conditions_check = [
                'nick_name' => $data['nick_name']
            ];
            $check_nick_name = $listUserServicesService->list($conditions_check);

            if (!$check_nick_name->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pháp Danh đã tồn tại, vui lòng đặt tên khác!'
                ], 200);
            }

            $data_temp = $listTemporaryUserServicesService->find($id);

            if (is_null($data_temp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID'
                ], 400);
            }

            $last_user = $listUserServicesService->getLastest();
            $uid = 'CPL_00001';
            if (!is_null($last_user)) {
                $uid = create_uid($last_user->id);
            }

            $request->merge([
                'nick_name' => $data['nick_name'],
                'uid' => $uid,
            ]);

            $user = $createUserServiceService->updateOrCreate($data_temp);

            $data_temp->update(['approved' => 1, 'temporary_user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(
        Request $request,
        ListTemporaryUserServicesService $listTemporaryUserServicesService,
        $id
    ) {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ID'
                ], 400);
            }
            $data = $listTemporaryUserServicesService->find($id);
            $data->delete();
            return redirect()->route('admin.quyy.list');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
