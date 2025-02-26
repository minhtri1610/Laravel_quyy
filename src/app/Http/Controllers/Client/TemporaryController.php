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

    public function store(CreateTemporaryUserServiceService $createTeamporary, Request $request)
    {
        try {
            if ($createTeamporary !== null && $request !== null) {
                if ($createTeamporary->passesValidation()) {
                    $createTeamporary->create();
                    session()->flash('temporary_user_full_name', $request->input('full_name'));
                    return redirect()->route('client.quyy.success');
                } else {
                    return redirect()->back()->withErrors(['validation' => 'Dữ liệu không hợp lệ!']);
                }
            } else {
                return redirect()->back()->withErrors(['validation' => 'Dữ liệu không hợp lệ!']);
            }
        } catch (Throwable $th) {
            return response($th->getMessage(), 500);
        }
    }

    public function success()
    {
        return view('client.temporary-users.success');
    }
}
