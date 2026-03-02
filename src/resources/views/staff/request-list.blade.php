@extends('layouts.staff')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-list.css') }}">
@endsection

@section('content')

<div class="request-list-container">

    <h1>申請一覧</h1>

    {{-- タブ --}}
    <div class="tab-menu">
        <a href="?tab=pending" class="tab-btn {{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="?tab=approved" class="tab-btn {{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <table class="request-table">
        <thead>
            <tr>
                <th>状態</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>

        <tbody>
            @php
            $list = $tab === 'pending' ? $pending : $approved;
            @endphp

            @forelse ($list as $r)
            <tr>
                <td>{{ $r->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                <td>{{ $r->attendance->work_date }}</td>
                <td>{{ $r->reason }}</td>
                <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="/attendance/detail/{{ $r->attendance_id }}" class="detail-btn">詳細</a>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection