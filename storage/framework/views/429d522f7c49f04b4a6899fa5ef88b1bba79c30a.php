<?php $__env->startSection('title', 'Quản Lý Nạp Ví'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
    <h2 style="margin:0; font-size:1.7rem;">💳 Quản Lý Nạp Ví</h2>
    <a href="/" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Về Dashboard</a>
</div>

<div class="chart-container" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('wallet-topups.index')); ?>" style="display:grid; grid-template-columns: 1fr 1fr auto auto; gap:0.75rem; align-items:end;">
        <div>
            <label style="display:block; font-weight:600; margin-bottom:0.4rem;">Thành viên</label>
            <input type="text" name="member" value="<?php echo e(request('member')); ?>" placeholder="Tên hoặc email thành viên">
        </div>
        <div>
            <label style="display:block; font-weight:600; margin-bottom:0.4rem;">Trạng thái</label>
            <select name="status">
                <option value="">Tất cả</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Đã duyệt</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lọc</button>
        <a href="<?php echo e(route('wallet-topups.index')); ?>" class="btn btn-secondary" style="text-decoration:none;">Đặt lại</a>
    </form>
</div>

<div class="chart-container">
    <h3 class="chart-title">Danh sách yêu cầu (<?php echo e($topupLogs->total()); ?>)</h3>

    <?php if($topupLogs->count() > 0): ?>
        <div style="overflow:auto;">
            <table class="table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Thành viên</th>
                        <th>Số tiền</th>
                        <th>Ghi chú</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $topupLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $amount = (int) data_get($log->properties, 'amount', 0);
                            $note = data_get($log->properties, 'note');
                            $status = data_get($log->properties, 'status', $log->event === 'approved' ? 'approved' : 'pending');
                        ?>
                        <tr>
                            <td><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <strong><?php echo e($log->causer->name ?? 'N/A'); ?></strong>
                                <div style="font-size:0.8rem; color:#64748b;"><?php echo e($log->causer->email ?? ''); ?></div>
                            </td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo e(number_format($amount)); ?> VND</td>
                            <td style="max-width:280px; color:#475569;"><?php echo e($note ?: 'Không có'); ?></td>
                            <td>
                                <?php if($status === 'approved' || $log->event === 'approved'): ?>
                                    <span style="background:#d1fae5;color:#065f46;padding:0.25rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:600;">Đã duyệt</span>
                                <?php else: ?>
                                    <span style="background:#fef3c7;color:#92400e;padding:0.25rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:600;">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($status === 'pending' && $log->event === 'requested'): ?>
                                    <form action="<?php echo e(route('wallet-topups.approve', $log->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Duyệt yêu cầu nạp ví này?')">Duyệt</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:#64748b; font-size:0.85rem;">Đã xử lý</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">
            <?php echo e($topupLogs->links()); ?>

        </div>
    <?php else: ?>
        <div style="padding:1rem; color:#64748b;">Không có yêu cầu nạp ví phù hợp bộ lọc.</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/wallet_topups/index.blade.php ENDPATH**/ ?>