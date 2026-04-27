@extends('layout')

@section('title', 'Sửa Người Dùng')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/users" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 style="margin-bottom: 1.5rem;">✏️ Sửa Người Dùng</h2>

    <form action="/users/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên *</label>
            <input type="text" name="name" id="name" required value="{{ $user->name }}">
            @error('name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" required value="{{ $user->email }}">
            @error('email')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mật Khẩu (để trống nếu không đổi)</label>
            <input type="password" name="password" id="password">
            @error('password')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone">Điện Thoại</label>
                <input type="tel" name="phone" id="phone" value="{{ $user->phone }}">
            </div>

            <div class="form-group">
                <label for="role">Vai Trò *</label>
                <select name="role" id="role" required>
                    <option value="member" @if ($user->role === 'member') selected @endif>Thành Viên</option>
                    <option value="librarian" @if ($user->role === 'librarian') selected @endif>Thủ Thư</option>
                    <option value="admin" @if ($user->role === 'admin') selected @endif>Quản Trị Viên</option>
                </select>
                @error('role')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address">Địa Chỉ</label>
            <textarea name="address" id="address" rows="2">{{ $user->address }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="wallet_balance">Số Dư Ví (VND)</label>
                <input type="number" name="wallet_balance" id="wallet_balance" min="0" value="{{ old('wallet_balance', $user->wallet_balance ?? 0) }}">
            </div>

            <div class="form-group">
                <label for="outstanding_debt">Nợ Tồn (VND)</label>
                <input type="number" name="outstanding_debt" id="outstanding_debt" min="0" value="{{ old('outstanding_debt', $user->outstanding_debt ?? 0) }}">
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">✓ Lưu Thay Đổi</button>
            <a href="/users" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
