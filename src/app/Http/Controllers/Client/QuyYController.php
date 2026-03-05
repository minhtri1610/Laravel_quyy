<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\UserService\ListUserServicesService;
use App\Services\UserService\UpdateUserServiceService;
use App\Traits\QRcodeGenerateTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QuyYController extends Controller
{

    use QRcodeGenerateTrait;

    public function search(
        Request $request,
        ListUserServicesService $listUserServices,
    ) {
        $key_word = $request->get('key-word');
        $conditions = [
            'keys' => $key_word,
        ];
        $users = [];
        if (!empty($key_word)) {
            $users = $listUserServices->paginate($conditions, 10)->appends(['key-word' => $key_word]);
        }

        return view('client.quyy.search', compact('users'));
    }

    public function checkDetail(
        Request $request,
        ListUserServicesService $listUserServices,
        UpdateUserServiceService $updateUserService,
    ) {
        try {
            $uid = $request->uid;
            $phone = $request->phone;
            $user = $listUserServices->findByUid($uid);

            if (is_null($user) || $user->is_active == 0 || $user->phone != $phone || is_null($user->uid)) {
                return new JsonResponse(['message' => 'SĐT không trùng khớp!', 'status' => false], 200);
            }
            if (empty($user->qr_code)) {
                $url = route('client.quyy.short_detail', ['uid' => $uid]);
                $qr_code = $this->createQR($url);
                $input = [
                    'qr_code' => $qr_code,
                    'id' => $user->id,
                ];

                $updateUserService->update($input);
            } else {
                $qr_code = $user->qr_code;
            }

            return new JsonResponse(['url' => route('client.quyy.short_detail', ['uid' => $uid]), 'qr_code' => $qr_code, 'status' => true], 200);
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }

    public function detail(
        $uid,
        Request $request,
        ListUserServicesService $listUserServices,
    ) {
        // Xử lý ngược tương thích với mã QR cũ (có dạng hash_uuid)
        if (strpos($uid, '_') !== false) {
            $parts = explode('_', $uid);
            $uid = end($parts);
        }

        $user = $listUserServices->findByUid($uid);
        if (is_null($user) || $user->is_active == 0 || is_null($user->uid)) {
            return abort(404);
        }
        return view('client.quyy.detail', compact('user'));
    }

    public function shortDetail(
        $uid,
        Request $request,
        ListUserServicesService $listUserServices,
    ) {
        $user = $listUserServices->findByUid($uid);
        if (is_null($user) || $user->is_active == 0 || is_null($user->uid)) {
            return abort(404);
        }
        return view('client.quyy.detail', compact('user'));
    }

}
