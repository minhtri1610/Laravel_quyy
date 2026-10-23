<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $role = strtolower($role);
        $roleConfig = config('conts.roles');

        if (!array_key_exists($role, $roleConfig)) {
            abort(403, 'Unauthorized action.');
        }

        $requiredCode = $roleConfig[$role];
        $adminCodes = [$roleConfig['super-admin'], $roleConfig['admin']];
        $isAdminRole = in_array($requiredCode, $adminCodes, true);
        $guard = $isAdminRole ? 'admin' : 'user';

        $auth = Auth::guard($guard);

        if (!$auth->check()) {
            return redirect($guard === 'admin' ? route('admin.login') : route('client.login'));
        }

        $user = $auth->user();

        // Tài khoản bị khóa khi đang đăng nhập: đăng xuất ngay, không chờ hết session
        if ($isAdminRole && !$user->is_active) {
            $auth->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->withErrors(['email' => __('lables.pg_login.error_inactive')]);
        }

        // Mã role nhỏ hơn có quyền cao hơn: super-admin (1) vào được trang của admin (2)
        $allowedCodes = $isAdminRole
            ? array_filter($adminCodes, fn ($code) => (int) $code <= (int) $requiredCode)
            : [$requiredCode];
        $userCodes = array_map('strval', (array) ($user->roles ?? []));

        if (empty(array_intersect($userCodes, $allowedCodes))) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
