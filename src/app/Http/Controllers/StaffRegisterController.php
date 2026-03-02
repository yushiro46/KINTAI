<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class StaffRegisterController extends Controller
{
    // 新規登録フォーム表示
    public function showForm()
    {
        return view('auth.staff-register');
    }

    // 新規登録処理
    public function store(Request $request)
    {
        // 入力チェック
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // スタッフ登録
        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 登録後そのままログイン
        Auth::guard('staff')->login($staff);

        // 勤怠画面へリダイレクト
        return redirect()->route('staff.attendance');
    }
}
