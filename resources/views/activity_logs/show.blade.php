@extends('layout')

@section('title', 'Chi Tiết Nhật Ký')

@section('content')
<div class="container">
    <h2>🔍 Chi Tiết Nhật Ký Hoạt Động</h2>
    
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Thông Tin Chung</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Thời Gian</th>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Loại Hoạt Động</th>
                            <td>{{ ucfirst($log->log_name) }}</td>
                        </tr>
                        <tr>
                            <th>Sự Kiện</th>
                            <td>
                                @if($log->event == 'created')
                                    <span class="badge bg-success">Tạo Mới</span>
                                @elseif($log->event == 'updated')
                                    <span class="badge bg-warning">Cập Nhật</span>
                                @elseif($log->event == 'deleted')
                                    <span class="badge bg-danger">Xóa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Mô Tả</th>
                            <td>{{ $log->description }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h5>Thông Tin Người Thực Hiện</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Người Thực Hiện</th>
                            <td>{{ $log->causer ? $log->causer->name : 'System' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $log->causer ? $log->causer->email : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td><small>{{ $log->user_agent }}</small></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($log->properties)
            <div class="row">
                <div class="col-12">
                    <h5>Chi Tiết Dữ Liệu</h5>
                    <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">← Quay Lại</a>
            </div>
        </div>
    </div>
</div>
@endsection
