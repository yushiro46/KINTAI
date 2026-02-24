<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Staff;

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

    //スタッフ一覧
    public function index()
    {
        $staffs = Staff::all();

        return view('admin.staff-list', compact('staffs'));
    }

    //スタッフ別勤怠一覧
    public function monthly(Request $request, $staff_id)
    {
        $staff = Staff::findOrFail($staff_id);

        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $currentMonth = \Carbon\Carbon::create($year, $month, 1);
        $prevMonth    = $currentMonth->copy()->subMonth();
        $nextMonth    = $currentMonth->copy()->addMonth();

        $attendances = Attendance::where('staff_id', $staff->id)
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month)
            ->orderBy('work_date')
            ->get();

        return view('admin.attendance-monthly', compact(
            'staff',
            'attendances',
            'currentMonth',
            'prevMonth',
            'nextMonth'
        ));
    }

    //csv出力
    public function csvExport(Request $request, $staff_id)
    {
        $staff = Staff::findOrFail($staff_id);

        $year = $request->year;
        $month = $request->month;

        $attendances = Attendance::where('staff_id', $staff_id)
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month)
            ->orderBy('work_date')
            ->get();

        $filename = "{$staff->name}_{$year}年{$month}月_勤怠.csv";

        $csvData = "日付,出勤,休憩,退勤,実働時間\n";

        foreach ($attendances as $a) {

            $breakMinutes = ($a->break_start && $a->break_end)
                ? \Carbon\Carbon::parse($a->break_start)->diffInMinutes(\Carbon\Carbon::parse($a->break_end))
                : '-';

            $worked = $a->worked_minutes
                ? floor($a->worked_minutes / 60) . "時間 " . ($a->worked_minutes % 60) . "分"
                : '-';

            $csvData .= implode(",", [
                $a->work_date,
                $a->clock_in ?? '-',
                $breakMinutes,
                $a->clock_out ?? '-',
                $worked
            ]) . "\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
