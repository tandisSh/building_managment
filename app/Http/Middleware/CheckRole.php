<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->hasRole($role)) {
            // اگر کاربر نقش مورد نظر را نداشت، به داشبورد مربوط به نقش خودش ریدایرکت شود
            $redirectRoute = match(true) {
                $user->hasRole('super_admin') => 'admin.dashboard',
                $user->hasRole('manager') => 'manager.dashboard',
                $user->hasRole('resident') => 'resident.dashboard',
                default => 'home'
            };

            return redirect()->route($redirectRoute)->with('error', 'شما مجوز دسترسی به این صفحه را ندارید');
        }

        return $next($request);
    }
}
