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

        $guard = ($roleConfig[$role] === $roleConfig['super-admin'] || $roleConfig[$role] === $roleConfig['admin'])
            ? 'admin'
            : 'user';

        $auth = Auth::guard($guard);

        if (!$auth->check()) {
            return redirect($guard === 'admin' ? route('admin.login') : route('client.login'));
        }

        if ($auth->user()->roles[0] !== $roleConfig[$role]) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
