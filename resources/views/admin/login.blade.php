@extends('layouts.app')

@section('title', 'Masuk Admin — Kala Senja')

@section('content')
<main class="admin-page login-page">
    <div class="login-shell">
        <div class="login-photo">
            <img src="{{ asset('admin-room.png') }}" alt="Ruang Kala Senja" fetchpriority="high">
            <div class="login-photo-caption">
                <span>Owner</span>
                <strong>Kala Senja</strong>
            </div>
        </div>
        <div class="admin-panel login-panel">
            <span class="eyebrow"><span class="eyebrow-line"></span> Area khusus tim</span>
            <h1>Selamat datang<br><em>kembali.</em></h1>
            <p>Masuk untuk mengelola sajian Kala Senja.</p>
            <form action="{{ route('admin.login.store') }}" method="POST" class="admin-form">
                @csrf
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    @error('email')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label>Kata sandi
                    <input type="password" name="password" autocomplete="current-password" required>
                    @error('password')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label class="checkbox-label"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <button class="button button-dark form-submit" type="submit">Masuk ke admin <span aria-hidden="true">↗</span></button>
            </form>
            <a class="text-link" href="{{ route('home') }}">← Kembali ke beranda</a>
        </div>
    </div>
</main>
@endsection
