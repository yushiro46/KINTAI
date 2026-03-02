<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LateRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller

{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');

        // 管理者ログイン中
        if (Auth::guard('admin')->check()) {

            $pending = LateRequest::with(['staff', 'attendance'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            $approved = LateRequest::with(['staff', 'attendance'])
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.request-list', compact('pending', 'approved', 'tab'));
        }

        // スタッフログイン中
        $staffId = Auth::guard('staff')->id();

        $pending = LateRequest::with('attendance')
            ->where('staff_id', $staffId)
            ->where('status', 'pending')
            ->get();

        $approved = LateRequest::with('attendance')
            ->where('staff_id', $staffId)
            ->where('status', 'approved')
            ->get();

        return view('staff.request-list', compact('pending', 'approved', 'tab'));
    }
}
