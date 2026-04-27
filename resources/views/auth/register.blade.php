@extends('layout')

@section('title', 'Đăng Ký - Quản Lý Thư Viện')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0c4a6e 0%, #164e63 50%, #155e75 100%);">
    <div class="card" style="width: 100%; max-width: 480px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">✍️</div>
            <h1 style="margin: 0; font-size: 1.75rem; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Tạo Tài Khoản</h1>
            <p style="color: #6b7280; margin-top: 0.5rem;">Tham gia cộng đồng độc giả của chúng tôi</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf
            <div class="form-group">
                <label style="font-weight: 600;">Tên Đầy Đủ</label>
                <input type="text" name="name" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label style="font-weight: 600;">Email</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label style="font-weight: 600;">Số Điện Thoại</label>
                <input type="tel" name="phone" class="form-control" placeholder="0123456789" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group">
                <label style="font-weight: 600;">Mật Khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label style="font-weight: 600;">Xác Nhận Mật Khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                <i class="fas fa-user-plus"></i> Tạo Tài Khoản
            </button>
        </form>

        <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #e5e7eb;">

        <p style="text-align: center; color: #6b7280; margin: 0;">
            Đã có tài khoản?
            <a href="/login" style="color: #06b6d4; font-weight: 600; text-decoration: none;">Đăng nhập</a>
        </p>

        <div style="margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; border-left: 4px solid #0284c7;">
            <p style="margin: 0; color: #1e40af; font-weight: 600; font-size: 0.875rem;">
                <i class="fas fa-shield-alt"></i> Lợi Ích Thành Viên:
            </p>
            <ul style="margin: 0.5rem 0 0 1.5rem; color: #1e40af; font-size: 0.85rem;">
                <li>Mượn sách trực tuyến</li>
                <li>Đặt trước sách yêu thích</li>
                <li>Xem lịch sử mượn</li>
                <li>Nhận khuyến nghị sách</li>
            </ul>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #0c4a6e 0%, #164e63 50%, #155e75 100%) !important;
    }
</style>
@endsection
