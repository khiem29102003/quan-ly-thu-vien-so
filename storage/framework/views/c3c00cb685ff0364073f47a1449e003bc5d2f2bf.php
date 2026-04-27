<?php $__env->startSection('title', 'Duyệt Yêu Cầu Mượn'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>📝 Duyệt Yêu Cầu Mượn Sách</h2>
    <a href="/borrows" class="btn btn-secondary">Phiếu Mượn</a>
</div>

<div class="card" style="margin-bottom: 1rem; background: linear-gradient(135deg, rgba(14,165,233,0.08) 0%, rgba(2,132,199,0.05) 100%); border-left: 4px solid #0284c7;">
    <div style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Quy trình duyệt mượn</div>
    <div style="font-size: 0.9rem; color:#334155;">Thành viên gửi yêu cầu đặt sách → Thủ thư/Admin bấm <strong>Duyệt</strong> để tạo phiếu mượn ngay và trừ phí mượn (nếu có). Nếu không chấp thuận, bấm <strong>Từ chối</strong>.</div>
</div>

<div class="card" style="margin-bottom: 1.25rem;">
    <form method="GET" action="<?php echo e(route('borrows.reservations')); ?>" style="display:grid; grid-template-columns: 1fr 220px auto auto; gap:0.75rem; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:0.4rem; font-weight:600;">Tìm kiếm</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Tên thành viên, email, tên sách...">
        </div>
        <div>
            <label style="display:block; margin-bottom:0.4rem; font-weight:600;">Trạng thái</label>
            <select name="status">
                <option value="">Tất cả</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                <option value="confirmed" <?php echo e(request('status') === 'confirmed' ? 'selected' : ''); ?>>Đã duyệt</option>
                <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Đã từ chối/hủy</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lọc</button>
        <a href="<?php echo e(route('borrows.reservations')); ?>" class="btn btn-secondary" style="text-decoration:none;">Đặt lại</a>
    </form>
</div>

<div class="card">
    <?php if($reservations->count() > 0): ?>
        <div style="overflow:auto;">
            <table class="table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Thành viên</th>
                        <th>Sách</th>
                        <th>Lấy trước</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(optional($reservation->reserved_at)->format('d/m/Y H:i')); ?></td>
                            <td>
                                <strong><?php echo e($reservation->user->name ?? 'N/A'); ?></strong>
                                <div style="font-size:0.8rem; color:#64748b;"><?php echo e($reservation->user->email ?? ''); ?></div>
                            </td>
                            <td>
                                <strong><?php echo e($reservation->book->title ?? 'N/A'); ?></strong>
                                <div style="font-size:0.8rem; color:#64748b;"><?php echo e($reservation->book->author ?? ''); ?></div>
                            </td>
                            <td><?php echo e(optional($reservation->pickup_by)->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($reservation->status === 'pending'): ?>
                                    <span style="background:#fef3c7;color:#92400e;padding:0.2rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Chờ duyệt</span>
                                <?php elseif($reservation->status === 'confirmed'): ?>
                                    <span style="background:#d1fae5;color:#065f46;padding:0.2rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Đã duyệt</span>
                                <?php else: ?>
                                    <span style="background:#fee2e2;color:#991b1b;padding:0.2rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Đã từ chối/hủy</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($reservation->status === 'pending'): ?>
                                    <div style="display:flex; gap:0.45rem; flex-wrap:wrap;">
                                        <form action="<?php echo e(route('borrows.reservations.approve', $reservation->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Duyệt yêu cầu này và tạo phiếu mượn?')">Duyệt</button>
                                        </form>
                                        <form action="<?php echo e(route('borrows.reservations.reject', $reservation->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Từ chối yêu cầu mượn này?')">Từ chối</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span style="font-size:0.85rem; color:#64748b;">Đã xử lý</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            <?php echo e($reservations->links()); ?>

        </div>
    <?php else: ?>
        <p style="text-align:center; color:#64748b; padding:1rem;">Không có yêu cầu mượn phù hợp bộ lọc.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/borrows/reservations.blade.php ENDPATH**/ ?>