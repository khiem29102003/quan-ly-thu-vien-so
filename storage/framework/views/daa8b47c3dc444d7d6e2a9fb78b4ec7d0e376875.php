<?php $__env->startSection('title', 'Quản Lý Phiếu Mượn'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>📋 Quản Lý Phiếu Mượn</h2>
    <div style="display:flex; gap:0.5rem;">
        <a href="<?php echo e(route('borrows.reservations')); ?>" class="btn btn-info">📝 Duyệt Yêu Cầu Mượn</a>
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
                <input type="text" name="search" class="form-control" placeholder="Tên người dùng, tên sách..." value="<?php echo e(request('search')); ?>">
            </div>
            
            <!-- User -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Người Dùng</label>
                <select name="user_id" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php if(request('user_id') == $user->id): ?> selected <?php endif; ?>>
                            <?php echo e($user->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <!-- Status -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Trạng Thái</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <option value="borrowed" <?php if(request('status') == 'borrowed'): ?> selected <?php endif; ?>>Đang Mượn</option>
                    <option value="returned" <?php if(request('status') == 'returned'): ?> selected <?php endif; ?>>Đã Trả</option>
                </select>
            </div>
            
            <!-- Overdue -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Quá Hạn</label>
                <select name="overdue" class="form-control">
                    <option value="">-- Tất Cả --</option>
                    <option value="1" <?php if(request('overdue') == '1'): ?> selected <?php endif; ?>>Chỉ Quá Hạn</option>
                </select>
            </div>
            
            <!-- Date From -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Từ Ngày</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            
            <!-- Date To -->
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Đến Ngày</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
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
    <?php if($borrows->count() > 0): ?>
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
                <?php $__currentLoopData = $borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($borrow->user->name); ?></strong></td>
                        <td><?php echo e($borrow->book->title); ?></td>
                        <td><?php echo e($borrow->borrowed_at->format('d/m/Y')); ?></td>
                        <td>
                            <?php echo e($borrow->due_date->format('d/m/Y')); ?>

                            <?php if($borrow->isOverdue() && $borrow->status === 'borrowed'): ?>
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.75rem;">Quá hạn</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($borrow->returned_at ? $borrow->returned_at->format('d/m/Y') : '-'); ?></td>
                        <td>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; 
                                <?php if($borrow->status === 'borrowed'): ?> background: #dbeafe; color: #0c2d6b;
                                <?php elseif($borrow->status === 'returned'): ?> background: #d1fae5; color: #065f46;
                                <?php else: ?> background: #fee2e2; color: #991b1b;
                                <?php endif; ?>">
                                <?php echo e(ucfirst($borrow->status)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if(($borrow->borrow_fee ?? 0) > 0): ?>
                                <strong style="color:#0f766e;"><?php echo e(number_format($borrow->borrow_fee)); ?> ₫</strong>
                            <?php else: ?>
                                0 ₫
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($borrow->fine_amount > 0): ?>
                                <strong style="color: #ef4444;"><?php echo e(number_format($borrow->fine_amount)); ?> ₫</strong>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(($borrow->late_fee_collected ?? 0) > 0): ?>
                                <strong style="color:#166534;"><?php echo e(number_format($borrow->late_fee_collected)); ?> ₫</strong>
                            <?php else: ?>
                                0 ₫
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $remainDebt = max(0, ($borrow->late_fee ?? $borrow->fine_amount ?? 0) - ($borrow->late_fee_collected ?? 0));
                            ?>
                            <?php if($remainDebt > 0): ?>
                                <strong style="color:#b91c1c;"><?php echo e(number_format($remainDebt)); ?> ₫</strong>
                            <?php else: ?>
                                0 ₫
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($borrow->status === 'borrowed'): ?>
                                <form action="/borrows/<?php echo e($borrow->id); ?>/return" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Trả Sách</button>
                                </form>
                            <?php endif; ?>
                            <form action="/borrows/<?php echo e($borrow->id); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;" onclick="return confirm('Xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div style="margin-top: 2rem; text-align: center;">
            <?php echo e($borrows->links()); ?>

        </div>
    <?php else: ?>
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Không có phiếu mượn nào</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/borrows/index.blade.php ENDPATH**/ ?>