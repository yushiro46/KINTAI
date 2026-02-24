@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/staff-list.css') }}">
@endsection

@section('content')
<div class="admin-staff-list-container">

    <h1>スタッフ一覧</h1>

    <table class="staff-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th> {{-- ← 見出し＋詳細ボタンのセット --}}
            </tr>
        </thead>

        <tbody>
            @foreach ($staffs as $staff)
            <tr>
                {{-- スタッフ名 --}}
                <td>{{ $staff->name }}</td>

                {{-- メールアドレス --}}
                <td>{{ $staff->email }}</td>

                {{-- 月次勤怠（見出し + 詳細ボタン） --}}
                <td>
                    <div class="monthly-box">
                        <div class="monthly-label">月次勤怠</div>

                        <a href="{{ route('admin.attendance.monthly', ['staff_id' => $staff->id]) }}"
                            class="btn btn-detail">
                            詳細
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

@endsection