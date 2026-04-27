@extends('layout')

@section('title', 'Chi Tiết Người Dùng')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/users" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h2 style="margin-bottom: 1rem;">👤 {{ $user->name }}</h2>
            <table style="width: 100%; margin-bottom: 1.5rem;">
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Email:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Điện Thoại:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $user->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Vai Trò:</strong></td>
                    <td style="padding: 0.5rem 0;">
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
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Trạng Thái:</strong></td>
                    <td style="padding: 0.5rem 0;">
                        <span style="background: 
                            @if ($user->is_active) #d1fae5
                            @else #fee2e2
                            @endif; 
                            color: 
                            @if ($user->is_active) #065f46
                            @else #991b1b
                            @endif; 
                            padding: 0.25rem 0.75rem; border-radius: 20px;">
                            @if ($user->is_active) ✓ Hoạt Động @else ✗ Vô Hiệu Hóa @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Địa Chỉ:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $user->address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Tham Gia Từ:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div>
            <a href="/users/{{ $user->id }}/edit" class="btn btn-warning">✏️ Chỉnh Sửa</a>
            <form action="/users/{{ $user->id }}" method="POST" style="display: inline; margin-left: 0.5rem;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa người dùng?')">🗑️ Xóa</button>
            </form>
        </div>
    </div>

    <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">📚 Lịch Sử Mượn ({{ $user->borrows->count() }} lượt)</h3>
        @if ($user->borrows->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Sách</th>
                        <th>Mượn Từ</th>
                        <th>Hạn Trả</th>
                        <th>Trả Lại</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->borrows as $borrow)
                        <tr>
                            <td>{{ $borrow->book->title }}</td>
                            <td>{{ $borrow->borrowed_at->format('d/m/Y') }}</td>
                            <td>{{ $borrow->due_date->format('d/m/Y') }}</td>
                            <td>{{ $borrow->returned_at ? $borrow->returned_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; 
                                    @if ($borrow->status === 'borrowed') background: #dbeafe; color: #0c2d6b;
                                    @elseif ($borrow->status === 'returned') background: #d1fae5; color: #065f46;
                                    @else background: #fee2e2; color: #991b1b;
                                    @endif">
                                    {{ ucfirst($borrow->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #6b7280;">Chưa có lịch sử mượn</p>
        @endif
    </div>
</div>
@endsection
