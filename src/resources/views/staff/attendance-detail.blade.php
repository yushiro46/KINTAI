@extends('layouts.staff')

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
        {{-- この勤怠に対する申請があるか確認 --}}
        @php
        $lateRequest = \App\Models\LateRequest::where('attendance_id', $attendance->id)
        ->where('staff_id', $attendance->staff->id)
        ->where('status', 'pending')
        ->first();
        @endphp

        {{-- 申請がまだない場合（修正可能） --}}
        @if (!$lateRequest)

        <form action="{{ route('late.request.store') }}" method="POST">
            @csrf

            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
            <input type="hidden" name="staff_id" value="{{ $attendance->staff->id }}">

            <div class="detail-row">
                <label for="reason">備考：</label>
                <textarea name="reason" id="reason" rows="4" class="note-area"
                    required>{{ old('reason') }}</textarea>
            </div>

            <button type="submit" class="update-btn">修正</button>
        </form>

        @else

        {{-- 申請あり → 修正不可 --}}
        <p class="pending-message">
            承認待ちのため修正はできません
        </p>


        @endif


    </div>

</div>
@endsection