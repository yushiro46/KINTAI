@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail-container">

    <h1>申請詳細</h1>

    <div class="detail-card">

        <div class="detail-row">
            <label>スタッフ名：</label>
            <span>{{ $lateRequest->staff->name }}</span>
        </div>

        <div class="detail-row">
            <label>日付：</label>
            <span>{{ $lateRequest->attendance->work_date }}</span>
        </div>

        <div class="detail-row">
            <label>勤務時間：</label>
            <span>
                {{ $lateRequest->attendance->clock_in ?? '-' }}
                〜
                {{ $lateRequest->attendance->clock_out ?? '-' }}
            </span>
        </div>

        <div class="detail-row">
            <label>休憩時間：</label>
            <span>
                @if ($lateRequest->attendance->break_start && $lateRequest->attendance->break_end)
                {{ $lateRequest->attendance->break_start }} 〜 {{ $lateRequest->attendance->break_end }}
                @else
                休憩なし
                @endif
            </span>
        </div>

        <div class="detail-row">
            <label>申請理由：</label>
            <span>{{ $lateRequest->reason }}</span>
        </div>

        <div class="button-area">

            {{-- すでに承認済みならボタンを表示しない --}}
            @if ($lateRequest->status === 'approved')

            <p class="approved-text">✔ 承認済み</p>

            @else

            {{-- 承認ボタン --}}
            <form action="{{ route('admin.request.approve', $lateRequest->id) }}" method="POST">
                @csrf
                <button class="approve-btn">承認</button>
            </form>

            @endif

        </div>


    </div>

</div>
@endsection