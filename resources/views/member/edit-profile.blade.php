@extends('layout')

@section('title', 'Chỉnh Sửa Thông Tin - Tài Khoản')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0; font-size: 1.75rem;">⚙️ Cài Đặt Tài Khoản</h2>
    <a href="/member/dashboard" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
    <!-- Profile Form -->
    <div class="chart-container">
        <h3 class="chart-title">📝 Thông Tin Cá Nhân</h3>
        
        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600;">⚠️ Có lỗi xảy ra:</p>
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/member/profile" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">👤 Họ và Tên</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;" required>
                @error('name')
                    <p style="margin: 0.25rem 0 0 0; color: #dc2626; font-size: 0.85rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">✉️ Email</label>
                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; background: #f3f4f6; cursor: not-allowed;" disabled readonly>
                <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.85rem;">* Email không thể thay đổi</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="phone" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">📱 Số Điện Thoại</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="form-control" placeholder="0912345678" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                @error('phone')
                    <p style="margin: 0.25rem 0 0 0; color: #dc2626; font-size: 0.85rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="address" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">🏠 Địa Chỉ</label>
                <textarea id="address" name="address" rows="3" class="form-control" placeholder="Số nhà, đường phố, quận huyện, tỉnh/thành phố" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; font-family: sans-serif;">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                @error('address')
                    <p style="margin: 0.25rem 0 0 0; color: #dc2626; font-size: 0.85rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.75rem; font-weight: 600;">
                    <i class="fas fa-save"></i> Lưu Thay Đổi
                </button>
                <a href="/member/dashboard" class="btn btn-secondary" style="flex: 1; padding: 0.75rem; text-align: center; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    <!-- Account Info -->
    <div class="chart-container">
        <h3 class="chart-title">👤 Thông Tin Tài Khoản</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Role -->
            <div style="padding: 1rem; background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(8, 145, 178, 0.03) 100%); border-radius: 8px; border-left: 3px solid #06b6d4;">
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Vai Trò</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;">
                    @if (Auth::user()->role === 'member')
                        👤 Thành Viên
                    @elseif (Auth::user()->role === 'librarian')
                        📚 Thủ Thư
                    @else
                        👨‍💼 Quản Trị Viên
                    @endif
                </p>
            </div>

            <!-- Status -->
            <div style="padding: 1rem; background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.03) 100%); border-radius: 8px; border-left: 3px solid #10b981;">
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Trạng Thái</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;">
                    @if (Auth::user()->is_active)
                        <span style="display: inline-block; background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 16px; font-size: 0.9rem;">✓ Hoạt Động</span>
                    @else
                        <span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 16px; font-size: 0.9rem;">✗ Bị Khóa</span>
                    @endif
                </p>
            </div>

            <!-- Member Since -->
            <div style="padding: 1rem; background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(2, 132, 199, 0.03) 100%); border-radius: 8px; border-left: 3px solid #0ea5e9;">
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Tham Gia Từ</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
            </div>

            <!-- Member ID -->
            <div style="padding: 1rem; background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(217, 119, 6, 0.03) 100%); border-radius: 8px; border-left: 3px solid #f59e0b;">
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Mã Thành Viên</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;">#{{ Auth::user()->id }}</p>
            </div>
        </div>

        <!-- Member Benefits -->
        <div style="margin-top: 1.5rem; padding: 1rem; background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe;">
            <h4 style="margin: 0 0 0.75rem 0; color: #1e40af; font-weight: 600;">✨ Quyền Lợi Thành Viên</h4>
            <ul style="margin: 0; padding-left: 1.5rem; color: #1e40af; font-size: 0.95rem;">
                <li style="margin-bottom: 0.5rem;">Mượn tối đa 5 cuốn sách cùng lúc</li>
                <li style="margin-bottom: 0.5rem;">Thời hạn mượn 30 ngày, có thể gia hạn 2 lần</li>
                <li style="margin-bottom: 0.5rem;">Đặt sách trước, lấy trong 7 ngày</li>
                <li>Không giới hạn số lần gia hạn trực tuyến</li>
            </ul>
        </div>
    </div>
</div>
@endsection
