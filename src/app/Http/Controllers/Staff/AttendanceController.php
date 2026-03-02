<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\LateRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 勤怠画面表示
    public function index()
    {
        $staffId = Auth::guard('staff')->id();

        // 今日の勤怠を取得 or 新規作成
        $attendance = Attendance::firstOrCreate(
            [
                'staff_id' => $staffId,
                'work_date' => Carbon::today()->toDateString(),
            ],
            [
                'status' => 'off',
            ]
        );

        return view('staff.attendance', compact('attendance'));
    }

    // 出勤
    public function clockIn()
    {
        $attendance = $this->getTodayAttendance();

        if ($attendance->status === 'off') {
            $attendance->clock_in = Carbon::now()->format('H:i:s');
            $attendance->status = 'working';
            $attendance->save();
        }

        return $this->jsonResponse($attendance);
    }

    // 休憩開始
    public function breakIn()
    {
        $attendance = $this->getTodayAttendance();

        if ($attendance->status === 'working') {
            $attendance->break_start = Carbon::now()->format('H:i:s');
            $attendance->status = 'break';
            $attendance->save();
        }

        return $this->jsonResponse($attendance);
    }

    // 休憩終了
    public function breakOut()
    {
        $attendance = $this->getTodayAttendance();

        if ($attendance->status === 'break') {
            $attendance->break_end = Carbon::now()->format('H:i:s');
            $attendance->status = 'working';
            $attendance->save();
        }

        return $this->jsonResponse($attendance);
    }

    // 退勤
    public function clockOut()
    {
        $attendance = $this->getTodayAttendance();

        if ($attendance->status === 'working' || $attendance->status === 'break') {
            $attendance->clock_out = Carbon::now()->format('H:i:s');
            $attendance->status = 'finished';
            $attendance->save();
        }

        return $this->jsonResponse($attendance);
    }

    // 今日の勤怠データを取得
    private function getTodayAttendance()
    {
        $staffId = Auth::guard('staff')->id();

        return Attendance::where('staff_id', $staffId)
            ->where('work_date', Carbon::today()->toDateString())
            ->firstOrFail();
    }

    // フロント側（Ajax）に返すレスポンス
    private function jsonResponse($attendance)
    {
        $statusText = [
            'off' => '勤務外',
            'working' => '出勤中',
            'break' => '休憩中',
            'finished' => '退勤済み',
        ];

        return response()->json([
            'status' => $attendance->status,
            'status_text' => $statusText[$attendance->status],
        ]);
    }

    //スタッフログインで勤怠一覧
    public function monthly($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        $currentMonth = Carbon::create($year, $month, 1);
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $staff = Auth::guard('staff')->user();

        $attendances = Attendance::where('staff_id', $staff->id)
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month)
            ->orderBy('work_date')
            ->get();

        return view('staff.attendance-list', compact(
            'attendances',
            'currentMonth',
            'prevMonth',
            'nextMonth'
        ));
    }

    //勤怠詳細
    public function detail($id)
    {
        $attendance = Attendance::with('staff')->findOrFail($id);
        return view('staff.attendance-detail', compact('attendance'));
    }

    // 申請を保存
    public function store(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'staff_id' => 'required|exists:staff,id',
            'reason' => 'required|string'
        ]);

        LateRequest::create([
            'attendance_id' => $request->attendance_id,
            'staff_id' => $request->staff_id,
            'reason' => $request->reason,
            'status' => 'pending', // 承認待ち
        ]);
    }
}
