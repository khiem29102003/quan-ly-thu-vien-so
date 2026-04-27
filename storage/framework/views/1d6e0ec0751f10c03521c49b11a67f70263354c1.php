<?php $__env->startSection('title', $book->title); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom: 1.5rem;">
    <a href="/books" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card">
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 2rem;">
        <div style="border-radius: 8px; height: 300px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #22d3ee 100%);">
            <?php if($book->cover_image): ?>
                <img src="<?php echo e(route('books.cover', $book->id)); ?>" alt="<?php echo e($book->title); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <span style="color: white; font-size: 5rem;">📖</span>
            <?php endif; ?>
        </div>
        <div>
            <h2 style="margin-bottom: 0.5rem;"><?php echo e($book->title); ?></h2>
            <p style="color: #6b7280; font-size: 1.1rem; margin-bottom: 1rem;">by <?php echo e($book->author); ?></p>
            
            <table style="width: 100%; margin-bottom: 1.5rem;">
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Mã số tiêu chuẩn quốc tế của sách:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e($book->isbn); ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Danh Mục:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e($book->category->name); ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Nhà Xuất Bản:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e($book->publisher ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Năm Xuất Bản:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e($book->publication_year ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Ngôn Ngữ:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e($book->language); ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Tổng Bản Sao:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e($book->total_copies); ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Sẵn Có:</strong></td>
                    <td style="padding: 0.5rem 0;">
                        <span class="book-status <?php if($book->available_copies > 0): ?> status-available <?php else: ?> status-unavailable <?php endif; ?>">
                            <?php echo e($book->available_copies); ?> / <?php echo e($book->total_copies); ?>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Đánh Giá:</strong></td>
                    <td style="padding: 0.5rem 0; color: #f59e0b;">⭐ <?php echo e($book->rating); ?>/5</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Nguồn Sách:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">
                        <?php echo e($book->source_type ?? 'purchase'); ?>

                        <?php if($book->source_name): ?>
                            - <?php echo e($book->source_name); ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Phí Mượn:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e(number_format($book->borrow_fee ?? 0)); ?> đ</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Phạt Quá Hạn / Ngày:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;"><?php echo e(number_format($book->daily_late_fee ?? 5000)); ?> đ</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Loại:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">
                        <?php if($book->is_digital): ?>
                            Sách số (ebook)
                        <?php else: ?>
                            Sách vật lý
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 1.5rem;">
                <a href="/books/<?php echo e($book->id); ?>/edit" class="btn btn-warning">Chỉnh Sửa</a>
                <form action="/books/<?php echo e($book->id); ?>" method="POST" style="display: inline; margin-left: 0.5rem;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                </form>
            </div>
        </div>
    </div>

    <?php if($book->description): ?>
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
            <h3 style="margin-bottom: 1rem;">📝 Mô Tả</h3>
            <p style="color: #374151; line-height: 1.6;"><?php echo e($book->description); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/books/show.blade.php ENDPATH**/ ?>