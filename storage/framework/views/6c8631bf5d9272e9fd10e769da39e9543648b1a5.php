<?php $__env->startSection('title', 'Sách Đang Mượn'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0; font-size: 1.75rem;">📖 Sách Đang Mượn</h2>
    <a href="<?php echo e(route('member.dashboard')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<?php if(!empty($hasOverdueBorrow)): ?>
    <div style="margin-bottom: 1rem; background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; padding: 0.9rem 1rem; border-radius: 8px;">
        Bạn đang có sách quá hạn. Hãy ưu tiên trả sách để mở lại đầy đủ quyền truy cập.
    </div>
<?php endif; ?>

<div class="chart-container">
    <h3 class="chart-title">Danh Sách Hiện Tại (<?php echo e($borrows->total()); ?> cuốn)</h3>

    <?php if($borrows->count() > 0): ?>
        <div style="overflow-x:auto;">
            <table class="table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Tên Sách</th>
                        <th>Tác Giả</th>
                        <th>Ngày Mượn</th>
                        <th>Hạn Trả</th>
                        <th>Tình Trạng</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e(Str::limit($borrow->book->title, 35)); ?></strong></td>
                            <td><?php echo e(Str::limit($borrow->book->author, 25)); ?></td>
                            <td><?php echo e($borrow->borrowed_at->format('d/m/Y')); ?></td>
                            <td><?php echo e($borrow->due_date->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($borrow->due_date < now()): ?>
                                    <span style="background:#fee2e2;color:#991b1b;padding:0.25rem 0.6rem;border-radius:16px;font-size:0.8rem;font-weight:600;">⚠️ Quá hạn</span>
                                <?php else: ?>
                                    <span style="background:#d1fae5;color:#065f46;padding:0.25rem 0.6rem;border-radius:16px;font-size:0.8rem;font-weight:600;">✓ Còn hạn</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                                    <?php if($borrow->book->is_digital): ?>
                                        <?php if(!empty($hasOverdueBorrow)): ?>
                                            <span style="background:#fee2e2;color:#991b1b;padding:0.3rem 0.5rem;border-radius:4px;font-size:0.75rem;">Đọc bị khóa</span>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('member.read-book', $borrow->id)); ?>" class="btn btn-primary" style="padding:0.35rem 0.6rem; font-size:0.75rem; text-decoration:none;">Đọc</a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <form action="<?php echo e(route('member.return-book', $borrow->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success" style="padding:0.35rem 0.6rem; font-size:0.75rem;" onclick="return confirm('Xác nhận trả sách này ngay?')">Trả sách</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            <?php echo e($borrows->links()); ?>

        </div>
    <?php else: ?>
        <p style="color:#6b7280;">Hiện bạn không có sách nào đang mượn.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/member/current-borrows.blade.php ENDPATH**/ ?>