<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AdminAttendanceController extends Controller
{
    public function daily($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');

        $attendances = Attendance::with('staff')
            ->where('work_date', $date)
            ->get();

        return view('admin.daily', compact('date', 'attendances'));
    }

    public function detail($id)
    {
        $attendance = Attendance::with('staff')->findOrFail($id);

        return view('admin.attendance-detail', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->note = $request->note;
        $attendance->save();

        return redirect()->back()->with('success', '勤怠情報を更新しました。');
    }
}
