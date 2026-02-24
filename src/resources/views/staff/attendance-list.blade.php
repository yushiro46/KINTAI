@extends('layouts.staff')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-monthly.css') }}">
@endsection

@section('content')
<div class="monthly-attendance-container">

    {{-- 見出し --}}
    <h1>勤怠一覧</h1>

    {{-- 年月ナビゲーション --}}
    <div class="month-navigation">
        <a class="nav-btn"
            href="{{ route('staff.attendance.list', [
                'year' => $prevMonth->year,
                'month' => $prevMonth->month
            ]) }}">
            ＜ 前月
        </a>

        <span class="current-month">
            {{ $currentMonth->format('Y年m月') }}
        </span>

        <a class="nav-btn"
            href="{{ route('staff.attendance.list', [
                'year' => $nextMonth->year,
                'month' => $nextMonth->month
            ]) }}">
            翌月 ＞
        </a>
    </div>


    {{-- 月次勤怠テーブル --}}
    <table class="attendance-table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>休憩</th>
                <th>退勤</th>
                <th>実働時間</th>
                <th>詳細</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($attendances as $attendance)

            @php
            $breakMinutes = ($attendance->break_start && $attendance->break_end)
            ? \Carbon\Carbon::parse($attendance->break_start)
            ->diffInMinutes(\Carbon\Carbon::parse($attendance->break_end))
            : null;

            $workedHours = floor($attendance->worked_minutes / 60);
            $workedRemain = $attendance->worked_minutes % 60;
            @endphp

            <tr>
                <td>{{ $attendance->work_date }}</td>
                <td>{{ $attendance->clock_in ?? '-' }}</td>

                <td>
                    @if ($breakMinutes)
                    {{ $breakMinutes }}分
                    @else
                    -
                    @endif
                </td>

                <td>{{ $attendance->clock_out ?? '-' }}</td>

                <td>
                    @if ($attendance->worked_minutes)
                    {{ $workedHours }}時間 {{ $workedRemain }}分
                    @else
                    -
                    @endif
                </td>

                <td>
                    <a href="{{ route('staff.attendance.detail', ['id' => $attendance->id]) }}" class="detail-btn">
                        詳細
                    </a>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6">この月の勤怠データはありません。</td>
            </tr>
            @endforelse
        </tbody>

    </table>

</div>
@endsection