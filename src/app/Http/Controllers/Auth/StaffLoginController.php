<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/attendance'); // ← ログイン後の遷移
        }
    }
}
