@extends('layouts.header-admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/daily.css') }}">
@endsection

@section('content')
<div class="admin-attendance-container">

    {{-- タイトル --}}
    <h1>{{ \Carbon\Carbon::parse($date)->format('Y年m月d日') }} の勤怠</h1>

    {{-- 日付切り替え --}}
    <div class="date-navigation">
        <a class="date-btn"
            href="{{ route('admin.daily', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}">
            ＜ 前日
        </a>

        <span class="current-date">
            {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
        </span>

        <a class="date-btn"
            href="{{ route('admin.daily', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}">
            翌日 ＞
        </a>
    </div>

    {{-- 勤怠一覧テーブル --}}
    <table class="attendance-table">
        <thead>
            <tr>
                <th>スタッフ名</th>
                <th>出勤時間</th>
                <th>休憩時間</th>
                <th>退勤時間</th>
                <th>実働時間</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($attendances as $attendance)
            @php
            // 休憩時間の計算（1回のみ想定）
            $breakMinutes = null;
            if ($attendance->break_start && $attendance->break_end) {
            $breakMinutes = \Carbon\Carbon::parse($attendance->break_start)
            ->diffInMinutes(\Carbon\Carbon::parse($attendance->break_end));
            }

            // 実働時間 worked_minutes を「X時間 Y分」に変換
            $workedHours = $attendance->worked_minutes ? floor($attendance->worked_minutes / 60) : null;
            $workedRemainMinutes = $attendance->worked_minutes ? $attendance->worked_minutes % 60 : null;
            @endphp

            <tr>
                <td>{{ $attendance->staff->name }}</td>
                <td>{{ $attendance->clock_in ?? '-' }}</td>

                {{-- 休憩 --}}
                <td>
                    @if ($breakMinutes)
                    {{ $breakMinutes }}分
                    @else
                    -
                    @endif
                </td>

                <td>{{ $attendance->clock_out ?? '-' }}</td>

                {{-- 実働時間 --}}
                <td>
                    @if ($attendance->worked_minutes)
                    {{ $workedHours }}時間 {{ $workedRemainMinutes }}分
                    @else
                    -
                    @endif
                </td>

                <td>
                    <a href="{{route('admin.attendance.detail', $attendance->id) }}" class="detail-btn">詳細</a>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6">この日の勤怠データはありません。</td>
            </tr>
            @endforelse

        </tbody>
    </table>

</div>

@endsection