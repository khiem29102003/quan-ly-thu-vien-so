@extends('layout')

@section('title', 'Quản Lý Phiếu Mượn')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>📋 Quản Lý Phiếu Mượn</h2>
    <div style="display:flex; gap:0.5rem;">
        <a href="{{ route('borrows.reservations') }}" class="btn btn-info">📝 Duyệt Yêu Cầu Mượn</a>
        <a href="/borrows/create" class="btn btn-primary">+ Tạo Phiếu Mượn Mới</a>
    </div>
</div>

<div class="card" style="margin-bottom: 1rem; background: linear-gradient(135deg, rgba(6,182,212,0.08) 0%, rgba(8,145,178,0.06) 100%); border-left: 4px solid #06b6d4;">
    <div style="color: #0f172a; font-weight: 600; margin-bottom: 0.25rem;">Màn hình đã bật theo dõi tài chính mượn/trả</div>
    <div style="font-size: 0.9rem; color:#334155;">Hiển thị phí mượn, tiền phạt, số tiền đã thu từ ví và phần nợ còn lại của từng lượt mượn.</div>
</div>

<!-- Filter Panel -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="margin: 0; font-size: 1.1rem;">🔍 Bộ Lọc Tìm Kiếm</h3>
        <button onclick="document.getElementById('filterForm').classList.toggle('hidden')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
            <i class="fas fa-filter"></i> Hiện Bộ Lọc
        </button>
    </div>
    
    <form action="/borrows" method="GET" class="hidden" id="filterForm">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <!-- Search -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Tìm Kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tên người dùng, tên sách..." value="{{ request('search') }}">
            </div>
            
            <!-- User -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Người Dùng</label>
                <select name="user_id" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Trạng Thái</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <option value="borrowed" @if(request('status') == 'borrowed') selected @endif>Đang Mượn</option>
                    <option value="returned" @if(request('status') == 'returned') selected @endif>Đã Trả</option>
                </select>
            </div>
            
            <!-- Overdue -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Quá Hạn</label>
                <select name="overdue" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <option value="1" @if(request('overdue') == '1') selected @endif>Chỉ Quá Hạn</option>
                </select>
            </div>
            
            <!-- Date From -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Từ Ngày</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            
            <!-- Date To -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Đến Ngày</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Áp Dụng Lọc
            </button>
            <a href="/borrows" class="btn btn-secondary">
                <i class="fas fa-times"></i> Xóa Lọc
            </a>
        </div>
    </form>
</div>

<div class="card">
    @if ($borrows->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Người Dùng</th>
                    <th>Sách</th>
                    <th>Ngày Mượn</th>
                    <th>Hạn Trả</th>
                    <th>Ngày Trả</th>
                    <th>Trạng Thái</th>
                    <th>Phí Mượn</th>
                    <th>Tiền Phạt</th>
                    <th>Đã Thu Từ Ví</th>
                    <th>Nợ Còn Lại</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrows as $borrow)
                    <tr>
                        <td><strong>{{ $borrow->user->name }}</strong></td>
                        <td>{{ $borrow->book->title }}</td>
                        <td>{{ $borrow->borrowed_at->format('d/m/Y') }}</td>
                        <td>
                            {{ $borrow->due_date->format('d/m/Y') }}
                            @if ($borrow->isOverdue() && $borrow->status === 'borrowed')
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.75rem;">Quá hạn</span>
                            @endif
                        </td>
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
                        <td>
                            @if (($borrow->borrow_fee ?? 0) > 0)
                                <strong style="color:#0f766e;">{{ number_format($borrow->borrow_fee) }} ₫</strong>
                            @else
                                0 ₫
                            @endif
                        </td>
                        <td>
                            @if ($borrow->fine_amount > 0)
                                <strong style="color: #ef4444;">{{ number_format($borrow->fine_amount) }} ₫</strong>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if (($borrow->late_fee_collected ?? 0) > 0)
                                <strong style="color:#166534;">{{ number_format($borrow->late_fee_collected) }} ₫</strong>
                            @else
                                0 ₫
                            @endif
                        </td>
                        <td>
                            @php
                                $remainDebt = max(0, ($borrow->late_fee ?? $borrow->fine_amount ?? 0) - ($borrow->late_fee_collected ?? 0));
                            @endphp
                            @if ($remainDebt > 0)
                                <strong style="color:#b91c1c;">{{ number_format($remainDebt) }} ₫</strong>
                            @else
                                0 ₫
                            @endif
                        </td>
                        <td>
                            @if ($borrow->status === 'borrowed')
                                <form action="/borrows/{{ $borrow->id }}/return" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Trả Sách</button>
                                </form>
                            @endif
                            <form action="/borrows/{{ $borrow->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;" onclick="return confirm('Xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem; text-align: center;">
            {{ $borrows->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Không có phiếu mượn nào</p>
    @endif
</div>
@endsection
