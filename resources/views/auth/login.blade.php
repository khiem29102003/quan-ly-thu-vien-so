@extends('layout')

@section('title', 'Đăng Nhập - Quản Lý Thư Viện')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0c4a6e 0%, #164e63 50%, #155e75 100%);">
    <div class="card" style="width: 100%; max-width: 420px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
            <h1 style="margin: 0; font-size: 1.75rem; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Đăng Nhập</h1>
            <p style="color: #6b7280; margin-top: 0.5rem;">Khám phá thế giới sách của chúng tôi</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label style="font-weight: 600;">Email</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label style="font-weight: 600;">Mật Khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                <i class="fas fa-sign-in-alt"></i> Đăng Nhập
            </button>
        </form>

        <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #e5e7eb;">

        <p style="text-align: center; color: #6b7280; margin: 0;">
            Chưa có tài khoản?
            <a href="/register" style="color: #06b6d4; font-weight: 600; text-decoration: none;">Đăng ký ngay</a>
        </p>

        <div style="margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #e0f2fe 0%, #cffafe 100%); border-radius: 12px; border-left: 4px solid #06b6d4;">
            <p style="margin: 0; color: #0c2d6b; font-size: 0.875rem;">
                <i class="fas fa-info-circle"></i> <strong>Tài khoản Demo:</strong>
            </p>
            <p style="margin: 0.25rem 0 0 0; color: #0c2d6b; font-size: 0.8rem;">
                Email: member@library.test | Mật khẩu: password
            </p>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #0c4a6e 0%, #164e63 50%, #155e75 100%) !important;
    }
</style>
@endsection
