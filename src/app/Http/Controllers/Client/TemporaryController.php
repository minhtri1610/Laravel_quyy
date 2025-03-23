<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use App\Services\TemporaryUserService\CreateTemporaryUserServiceService;

class TemporaryController extends Controller
{
    public function index()
    {
        return view('client.temporary-users.index');
    }

    public function create()
    {
        return view('client.temporary-users.create');
    }

    public function store(Request $request, CreateTemporaryUserServiceService $service)
    {
        try {
            $service->create($request->all());
            session()->flash('temporary_user_full_name', $request->input('full_name'));
            return redirect()->route('client.quyy.success');
        } catch (Throwable $th) {
            return redirect()->back()->withErrors(['validation' => 'Dữ liệu không hợp lệ!']);
        }
    }

    public function success()
    {
        return view('client.temporary-users.success');
    }
}
