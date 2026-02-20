<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/coachtech-header-logo.png') }}" class="logo" alt="ロゴ">
        </div>

        <div class="header-right">
            @if (Auth::check())
            <a href="/admin/attendance/list" class="header-link">
                勤怠一覧
            </a>

            <a href="/admin/staff/list" class="header-link">
                スタッフ一覧
            </a>

            <a href="/stamp_correction_request/list" class="header-link">
                申請一覧
            </a>

            <!-- ログアウト（POST推奨） -->
            <form action="/admin/logout" method="post" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    ログアウト
                </button>
            </form>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>