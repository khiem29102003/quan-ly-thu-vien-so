<?php $__env->startSection('title', 'Nhật Ký Hoạt Động'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>📋 Nhật Ký Hoạt Động Hệ Thống</h2>
    
    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('activity-logs.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Loại Hoạt Động</label>
                    <select name="log_name" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="books" <?php echo e(request('log_name') == 'books' ? 'selected' : ''); ?>>Sách</option>
                        <option value="users" <?php echo e(request('log_name') == 'users' ? 'selected' : ''); ?>>Người dùng</option>
                        <option value="borrows" <?php echo e(request('log_name') == 'borrows' ? 'selected' : ''); ?>>Phiếu mượn</option>
                        <option value="reservations" <?php echo e(request('log_name') == 'reservations' ? 'selected' : ''); ?>>Đặt sách</option>
                        <option value="wallet_topups" <?php echo e(request('log_name') == 'wallet_topups' ? 'selected' : ''); ?>>Nạp ví</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sự Kiện</label>
                    <select name="event" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="created" <?php echo e(request('event') == 'created' ? 'selected' : ''); ?>>Tạo mới</option>
                        <option value="updated" <?php echo e(request('event') == 'updated' ? 'selected' : ''); ?>>Cập nhật</option>
                        <option value="deleted" <?php echo e(request('event') == 'deleted' ? 'selected' : ''); ?>>Xóa</option>
                        <option value="requested" <?php echo e(request('event') == 'requested' ? 'selected' : ''); ?>>Yêu cầu</option>
                        <option value="approved" <?php echo e(request('event') == 'approved' ? 'selected' : ''); ?>>Đã duyệt</option>
                        <option value="member_returned" <?php echo e(request('event') == 'member_returned' ? 'selected' : ''); ?>>Thành viên trả sách</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
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
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></td>
                            <td>
                                <?php if($log->log_name == 'books'): ?>
                                    <span class="badge bg-info">📚 Sách</span>
                                <?php elseif($log->log_name == 'users'): ?>
                                    <span class="badge bg-primary">👤 Users</span>
                                <?php elseif($log->log_name == 'borrows'): ?>
                                    <span class="badge bg-warning">📋 Phiếu mượn</span>
                                <?php elseif($log->log_name == 'reservations'): ?>
                                    <span class="badge bg-primary">❤️ Đặt sách</span>
                                <?php elseif($log->log_name == 'wallet_topups'): ?>
                                    <span class="badge bg-info">💳 Nạp ví</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Khác</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($log->event == 'created'): ?>
                                    <span class="badge bg-success">✓ Tạo</span>
                                <?php elseif($log->event == 'updated'): ?>
                                    <span class="badge bg-warning">✎ Sửa</span>
                                <?php elseif($log->event == 'deleted'): ?>
                                    <span class="badge bg-danger">✗ Xóa</span>
                                <?php elseif($log->event == 'requested'): ?>
                                    <span class="badge bg-warning">🕒 Yêu cầu</span>
                                <?php elseif($log->event == 'approved'): ?>
                                    <span class="badge bg-success">✅ Đã duyệt</span>
                                <?php elseif($log->event == 'member_returned'): ?>
                                    <span class="badge bg-info">↩️ Trả sách</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e($log->event); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($log->description); ?></td>
                            <td><?php echo e($log->causer ? $log->causer->name : 'System'); ?></td>
                            <td><small><?php echo e($log->ip_address); ?></small></td>
                            <td>
                                <div style="display:flex; gap:0.35rem; flex-wrap:wrap;">
                                    <a href="<?php echo e(route('activity-logs.show', $log->id)); ?>" class="btn btn-sm btn-info">Chi Tiết</a>
                                    <?php if($log->log_name === 'wallet_topups' && $log->event === 'requested' && in_array(auth()->user()->role, ['admin', 'librarian'])): ?>
                                        <form action="<?php echo e(route('wallet-topups.approve', $log->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Duyệt yêu cầu nạp ví này?')">Duyệt</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có nhật ký nào</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($logs->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/activity_logs/index.blade.php ENDPATH**/ ?>