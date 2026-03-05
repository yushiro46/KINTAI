@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<main class="admin-login-container">

    <h2 class="admin-login-title">管理者ログイン</h2>

    <form action="/admin/login" method="post" class="admin-login-form">
        @csrf

        <div class="form-group">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-input">
        </div>

        <div class="form__error">
            @error('email')
            {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">パスワード</label>
            <input type="password" name="password" class="form-input">
        </div>

        <div class="form__error">
            @error('password')
            {{ $message }}
            @enderror
        </div>

        <button type="submit" class="login-btn">
            管理者でログイン
        </button>

        <div class="form__error">
            @error('login')
            {{ $message }}
            @enderror
        </div>
    </form>

</main>

@endsection