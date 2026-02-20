@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<main class="register-container">

    <h2 class="register-title">新規登録</h2>

    <form action="/register" method="POST" class="register-form">
        @csrf

        <div class="form-group">
            <label class="form-label">名前</label>
            <input type="text" name="name" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">パスワード</label>
            <input type="password" name="password" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">パスワード確認</label>
            <input type="password" name="password_confirmation" class="form-input">
        </div>

        <button type="submit" class="submit-btn">
            登録する
        </button>

    </form>

    <a href="/login" class="login-link">
        ログインはこちら
    </a>

</main>

@endsection