@extends('layouts.header-admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail-container">

    {{-- タイトル --}}
    <h1>勤怠詳細</h1>

    <div class="detail-card">

        {{-- スタッフ名 --}}
        <div class="detail-row">
            <label>スタッフ名：</label>
            <span>{{ $attendance->staff->name }}</span>
        </div>

        {{-- 日付 --}}
        <div class="detail-row">
            <label>日付：</label>
            <span>{{ \Carbon\Carbon::parse($attendance->work_date)->format('Y-m-d') }}</span>
        </div>

        {{-- 出勤〜退勤 --}}
        <div class="detail-row">
            <label>勤務時間：</label>
            <span>
                {{ $attendance->clock_in ?? '-' }}
                〜
                {{ $attendance->clock_out ?? '-' }}
            </span>
        </div>

        {{-- 休憩 --}}
        <div class="detail-row">
            <label>休憩時間：</label>
            <span>
                @if ($attendance->break_start && $attendance->break_end)
                {{ $attendance->break_start }} 〜 {{ $attendance->break_end }}
                @else
                休憩なし
                @endif
            </span>
        </div>

        {{-- 備考（管理者が編集できる欄） --}}
        <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="detail-row">
                <label for="note">備考：</label>
                <textarea name="note" id="note" rows="4" class="note-area">{{ old('note', $attendance->note) }}</textarea>
            </div>

            <button type="submit" class="update-btn">修正する</button>
        </form>

    </div>

</div>
@endsection