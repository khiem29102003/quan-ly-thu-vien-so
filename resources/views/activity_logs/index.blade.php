@extends('layout')

@section('title', 'Nhật Ký Hoạt Động')

@section('content')
<div class="container">
    <h2>📋 Nhật Ký Hoạt Động Hệ Thống</h2>
    
    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Loại Hoạt Động</label>
                    <select name="log_name" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="books" {{ request('log_name') == 'books' ? 'selected' : '' }}>Sách</option>
                        <option value="users" {{ request('log_name') == 'users' ? 'selected' : '' }}>Người dùng</option>
                        <option value="borrows" {{ request('log_name') == 'borrows' ? 'selected' : '' }}>Phiếu mượn</option>
                        <option value="reservations" {{ request('log_name') == 'reservations' ? 'selected' : '' }}>Đặt sách</option>
                        <option value="wallet_topups" {{ request('log_name') == 'wallet_topups' ? 'selected' : '' }}>Nạp ví</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sự Kiện</label>
                    <select name="event" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Tạo mới</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Cập nhật</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Xóa</option>
                        <option value="requested" {{ request('event') == 'requested' ? 'selected' : '' }}>Yêu cầu</option>
                        <option value="approved" {{ request('event') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="member_returned" {{ request('event') == 'member_returned' ? 'selected' : '' }}>Thành viên trả sách</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Thời Gian</th>
                            <th>Loại</th>
                            <th>Sự Kiện</th>
                            <th>Mô Tả</th>
                            <th>Người Thực Hiện</th>
                            <th>IP Address</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if($log->log_name == 'books')
                                    <span class="badge bg-info">📚 Sách</span>
                                @elseif($log->log_name == 'users')
                                    <span class="badge bg-primary">👤 Users</span>
                                @elseif($log->log_name == 'borrows')
                                    <span class="badge bg-warning">📋 Phiếu mượn</span>
                                @elseif($log->log_name == 'reservations')
                                    <span class="badge bg-primary">❤️ Đặt sách</span>
                                @elseif($log->log_name == 'wallet_topups')
                                    <span class="badge bg-info">💳 Nạp ví</span>
                                @else
                                    <span class="badge bg-secondary">Khác</span>
                                @endif
                            </td>
                            <td>
                                @if($log->event == 'created')
                                    <span class="badge bg-success">✓ Tạo</span>
                                @elseif($log->event == 'updated')
                                    <span class="badge bg-warning">✎ Sửa</span>
                                @elseif($log->event == 'deleted')
                                    <span class="badge bg-danger">✗ Xóa</span>
                                @elseif($log->event == 'requested')
                                    <span class="badge bg-warning">🕒 Yêu cầu</span>
                                @elseif($log->event == 'approved')
                                    <span class="badge bg-success">✅ Đã duyệt</span>
                                @elseif($log->event == 'member_returned')
                                    <span class="badge bg-info">↩️ Trả sách</span>
                                @else
                                    <span class="badge bg-secondary">{{ $log->event }}</span>
                                @endif
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->causer ? $log->causer->name : 'System' }}</td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td>
                                <div style="display:flex; gap:0.35rem; flex-wrap:wrap;">
                                    <a href="{{ route('activity-logs.show', $log->id) }}" class="btn btn-sm btn-info">Chi Tiết</a>
                                    @if($log->log_name === 'wallet_topups' && $log->event === 'requested' && in_array(auth()->user()->role, ['admin', 'librarian']))
                                        <form action="{{ route('wallet-topups.approve', $log->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Duyệt yêu cầu nạp ví này?')">Duyệt</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có nhật ký nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
