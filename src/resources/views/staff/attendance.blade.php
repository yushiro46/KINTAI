@extends('layouts.header-staff')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-container">

    {{-- 日付と現在時刻 --}}
    <div class="datetime-box">
        <h2 id="current-date">{{ now()->format('Y-m-d') }}</h2>
        <h1 id="current-time"></h1>
    </div>

    {{-- 状態表示 --}}
    <div class="status-box">
        @if ($attendance->status === 'off')
        <h2 id="status-text">勤務外</h2>
        @elseif ($attendance->status === 'working')
        <h2 id="status-text">出勤中</h2>
        @elseif ($attendance->status === 'break')
        <h2 id="status-text">休憩中</h2>
        @elseif ($attendance->status === 'finished')
        <h2 id="status-text">退勤済み</h2>
        <p>お疲れ様でした！</p>
        @endif
    </div>

    {{-- ボタン表示（状態で出し分け） --}}
    <div class="button-box" id="button-area">

        @if ($attendance->status === 'off')
        <button class="btn" id="clock-in-btn">出勤</button>

        @elseif ($attendance->status === 'working')
        <button class="btn" id="break-in-btn">休憩入</button>
        <button class="btn" id="clock-out-btn">退勤</button>

        @elseif ($attendance->status === 'break')
        <button class="btn" id="break-out-btn">休憩戻</button>

        @elseif ($attendance->status === 'finished')
        {{-- 何も表示しない --}}
        @endif

    </div>

</div>

<script>
    // -------------------------------
    // リアルタイム時刻表示
    // -------------------------------
    function updateTime() {
        const now = new Date();
        document.getElementById("current-time").textContent =
            now.toLocaleTimeString('ja-JP', {
                hour12: false
            });
    }
    setInterval(updateTime, 1000);
    updateTime();

    // 汎用AJAX送信関数
    function post(url, callback) {
        fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content
                },
            })
            .then(res => res.json())
            .then(data => callback(data))
            .catch(err => console.error(err));
    }

    // UI 更新処理
    function updateUI(data) {
        document.getElementById("status-text").textContent = data.status_text;

        let buttons = "";

        if (data.status === "working") {
            buttons = `
            <button class="btn" id="break-in-btn">休憩入</button>
            <button class="btn" id="clock-out-btn">退勤</button>
        `
        }

        if (data.status === "break") {
            buttons = `<button class="btn" id="break-out-btn">休憩戻</button>`;
        }

        if (data.status === "finished") {
            buttons = "";
        }

        if (data.status === "off") {
            buttons = `<button class="btn" id="clock-in-btn">出勤</button>`;
        }

        document.getElementById("button-area").innerHTML = buttons;
        attachEvents();
    }

    // イベント再登録
    function attachEvents() {

        const btnIn = document.getElementById("clock-in-btn");
        if (btnIn) btnIn.onclick = () => post("/attendance/clock-in", updateUI);

        const btnBreakIn = document.getElementById("break-in-btn");
        if (btnBreakIn) btnBreakIn.onclick = () => post("/attendance/break-in", updateUI);

        const btnBreakOut = document.getElementById("break-out-btn");
        if (btnBreakOut) btnBreakOut.onclick = () => post("/attendance/break-out", updateUI);

        const btnOut = document.getElementById("clock-out-btn");
        if (btnOut) btnOut.onclick = () => post("/attendance/clock-out", updateUI);
    }

    attachEvents();
</script>
@endsection