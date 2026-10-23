<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => __('lables.pg_login.email_required'),
            'email.email' => __('lables.pg_login.email_invalid'),
            'password.required' => __('lables.pg_login.password_required'),
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // Đúng mật khẩu nhưng tài khoản đã bị khóa
            if (!Auth::guard('admin')->user()->is_active) {
                Auth::guard('admin')->logout();
                return redirect()->back()
                    ->withErrors(['email' => __('lables.pg_login.error_inactive')])
                    ->onlyInput('email', 'remember');
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return redirect()->back()
            ->withErrors(['email' => __('lables.pg_login.error_credentials')])
            ->onlyInput('email', 'remember');
    }


    public function resetPassword(Request $request)
    {
        return view('admin.auth.reset-password');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin.login'));
    }
}
