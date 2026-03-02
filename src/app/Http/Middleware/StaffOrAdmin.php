<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StaffOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 管理者 or スタッフがログインしていればOK
        if (Auth::guard('admin')->check() || Auth::guard('staff')->check()) {
            return $next($request);
        }

        // どちらもログインしていない → スタッフログインへ
        return redirect()->route('staff.login');
    }
}
