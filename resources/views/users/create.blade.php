@extends('layout')

@section('title', 'Thêm Người Dùng')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/users" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 style="margin-bottom: 1.5rem;">👤 Thêm Người Dùng Mới</h2>

    <form action="/users" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Tên *</label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}">
            @error('name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}">
            @error('email')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mật Khẩu *</label>
            <input type="password" name="password" id="password" required>
            @error('password')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone">Điện Thoại</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="role">Vai Trò *</label>
                <select name="role" id="role" required>
                    <option value="member" selected>Thành Viên</option>
                    <option value="librarian">Thủ Thư</option>
                    <option value="admin">Quản Trị Viên</option>
                </select>
                @error('role')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address">Địa Chỉ</label>
            <textarea name="address" id="address" rows="2">{{ old('address') }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="wallet_balance">Số Dư Ví (VND)</label>
                <input type="number" name="wallet_balance" id="wallet_balance" min="0" value="{{ old('wallet_balance', 100000) }}">
            </div>

            <div class="form-group">
                <label for="outstanding_debt">Nợ Tồn (VND)</label>
                <input type="number" name="outstanding_debt" id="outstanding_debt" min="0" value="{{ old('outstanding_debt', 0) }}">
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">✓ Thêm Người Dùng</button>
            <a href="/users" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
