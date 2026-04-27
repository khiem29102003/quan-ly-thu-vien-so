@extends('layout')

@section('title', 'Quản Lý Người Dùng')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>👥 Quản Lý Người Dùng</h2>
    <a href="/users/create" class="btn btn-primary">+ Thêm Người Dùng</a>
</div>

<!-- Filter Panel -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="margin: 0; font-size: 1.1rem;">🔍 Bộ Lọc Tìm Kiếm</h3>
        <button onclick="document.getElementById('filterForm').classList.toggle('hidden')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
            <i class="fas fa-filter"></i> Hiện Bộ Lọc
        </button>
    </div>
    
    <form action="/users" method="GET" class="hidden" id="filterForm">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <!-- Search -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Tìm Kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tên, email, điện thoại..." value="{{ request('search') }}">
            </div>
            
            <!-- Role -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Vai Trò</label>
                <select name="role" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <option value="admin" @if(request('role') == 'admin') selected @endif>Admin</option>
                    <option value="librarian" @if(request('role') == 'librarian') selected @endif>Thủ Thư</option>
                    <option value="member" @if(request('role') == 'member') selected @endif>Thành Viên</option>
                </select>
            </div>
            
            <!-- Status -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Trạng Thái</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <option value="active" @if(request('status') == 'active') selected @endif>Hoạt Động</option>
                    <option value="inactive" @if(request('status') == 'inactive') selected @endif>Vô Hiệu Hóa</option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Áp Dụng Lọc
            </button>
            <a href="/users" class="btn btn-secondary">
                <i class="fas fa-times"></i> Xóa Lọc
            </a>
        </div>
    </form>
</div>

<div class="card">
    @if ($users->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Điện Thoại</th>
                    <th>Vai Trò</th>
                    <th>Số Dư Ví</th>
                    <th>Nợ Tồn</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span style="background: 
                                @if ($user->role === 'admin') #dbeafe
                                @elseif ($user->role === 'librarian') #fed7aa
                                @else #d1fae5
                                @endif;
                                color: 
                                @if ($user->role === 'admin') #0c2d6b
                                @elseif ($user->role === 'librarian') #92400e
                                @else #065f46
                                @endif;
                                padding: 0.25rem 0.75rem; border-radius: 20px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ number_format($user->wallet_balance ?? 0) }} đ</td>
                        <td>
                            @if (($user->outstanding_debt ?? 0) > 0)
                                <strong style="color:#b91c1c;">{{ number_format($user->outstanding_debt) }} đ</strong>
                            @else
                                0 đ
                            @endif
                        </td>
                        <td>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; 
                                @if ($user->is_active) background: #d1fae5; color: #065f46;
                                @else background: #fee2e2; color: #991b1b;
                                @endif">
                                @if ($user->is_active) Hoạt Động @else Vô Hiệu Hóa @endif
                            </span>
                        </td>
                        <td>
                            <a href="/users/{{ $user->id }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Chi Tiết</a>
                            <a href="/users/{{ $user->id }}/edit" class="btn btn-warning" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; margin-left: 0.25rem;">Sửa</a>
                            <form action="/users/{{ $user->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; margin-left: 0.25rem;" onclick="return confirm('Xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem; text-align: center;">
            {{ $users->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Không có người dùng nào</p>
    @endif
</div>
@endsection
