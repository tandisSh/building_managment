<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // بررسی نقش با روش تضمینی
        if (!$this->checkUserRole($user, $role)) {
            abort(403, 'دسترسی ممنوع');
        }

        return $next($request);
    }

    protected function checkUserRole($user, string $role): bool
    {
        // روش کاملاً مطمئن بدون وابستگی به مدل Role
        return in_array($role, $user->roles->pluck('name')->toArray());
    }
}
