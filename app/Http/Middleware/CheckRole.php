<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // استفاده از Auth facade به جای تابع auth()
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // استفاده از مدل User
        if (!User::hasRole($role)) { // یا Auth::user()->hasRole($role)
            abort(403, 'دسترسی غیرمجاز!');
        }

        return $next($request);
    }
}
