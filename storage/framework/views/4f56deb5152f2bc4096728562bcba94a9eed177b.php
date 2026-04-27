<?php $__env->startSection('title', $book->title . ' - Thư Viện'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
    <a href="/member/browse" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    <!-- Book Cover & Actions -->
    <div class="chart-container">
        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 5rem; margin-bottom: 1.5rem; position: relative; box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);">
            <i class="fas fa-book"></i>
            <?php if($isReserved): ?>
                <span style="position: absolute; top: 10px; right: 10px; background: #ec4899; color: white; padding: 0.7rem 1.2rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">❤️ Đã Đặt</span>
            <?php elseif($isBorrowed): ?>
                <span style="position: absolute; top: 10px; right: 10px; background: #3b82f6; color: white; padding: 0.7rem 1.2rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">📖 Đang Mượn</span>
            <?php endif; ?>
        </div>

        <!-- Status Tags -->
        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <?php if($book->available_copies > 0): ?>
                    <span style="background: #d1fae5; color: #065f46; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">✓ Có sẵn (<?php echo e($book->available_copies); ?> bản)</span>
                <?php else: ?>
                    <span style="background: #fee2e2; color: #991b1b; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">✗ Hết sách</span>
                <?php endif; ?>

                <?php if($book->is_digital): ?>
                    <span style="background: #ede9fe; color: #5b21b6; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">📱 Sách số</span>
                <?php endif; ?>
                
                <?php if($book->language): ?>
                    <span style="background: #e0e7ff; color: #4338ca; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.9rem;">🌐 <?php echo e($book->language); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Button -->
        <?php if($isBorrowed): ?>
            <button class="btn btn-info" disabled style="width: 100%; padding: 1rem; font-size: 1rem; font-weight: 600;">
                <i class="fas fa-book"></i> Bạn Đang Mượn
            </button>

            <?php if(isset($activeBorrow)): ?>
                <form action="<?php echo e(route('member.return-book', $activeBorrow->id)); ?>" method="POST" style="margin-top: 0.75rem;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem; font-size: 1rem; font-weight: 600;" onclick="return confirm('Bạn muốn trả sách này ngay?')">
                        <i class="fas fa-undo"></i> Trả Sách Ngay
                    </button>
                </form>
            <?php endif; ?>

            <?php if($book->is_digital && isset($activeBorrow)): ?>
                <?php if(!empty($hasOverdueBorrow)): ?>
                    <div style="margin-top: 0.75rem; background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 8px; font-size: 0.85rem;">
                        Đang có sách quá hạn nên tạm khóa quyền đọc sách số.
                    </div>
                <?php else: ?>
                    <a href="/member/read/<?php echo e($activeBorrow->id); ?>" class="btn btn-primary" style="display:block; text-align:center; margin-top:0.75rem; width: 100%; padding: 1rem; font-size: 1rem; font-weight: 600; text-decoration:none;">
                        <i class="fas fa-book-reader"></i> Đọc Ngay
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        <?php elseif($isReserved): ?>
            <form action="/member/cancel-reservation/<?php echo e($userReservation->id ?? ''); ?>" method="POST" style="margin-bottom: 0.75rem;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 1rem; font-size: 1rem; font-weight: 600;" onclick="return confirm('Hủy đặt sách này?')">
                    <i class="fas fa-times"></i> Hủy Đặt
                </button>
            </form>
            <?php if($userReservation): ?>
                <div style="background: #dbeafe; color: #0c4a6e; padding: 0.75rem; border-radius: 8px; font-size: 0.85rem;">
                    <p style="margin: 0; font-weight: 600;">Lấy trước: <strong><?php echo e($userReservation->pickup_by->format('d/m/Y')); ?></strong></p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <form action="/member/reserve/<?php echo e($book->id); ?>" method="POST" style="margin-bottom: 0.75rem;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem; font-weight: 600;" <?php if($book->available_copies <= 0): ?> disabled <?php endif; ?>>
                    <i class="fas fa-heart"></i> Đặt Sách Này
                </button>
            </form>
            <p style="color: #6b7280; font-size: 0.85rem; text-align: center;">Sẽ lấy trong 7 ngày</p>
        <?php endif; ?>
    </div>

    <!-- Book Details -->
    <div class="chart-container">
        <h1 style="margin: 0 0 1rem 0; font-size: 2rem; color: #1f2937;"><?php echo e($book->title); ?></h1>
        
        <!-- Metadata -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Tác Giả</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;"><?php echo e($book->author); ?></p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Danh Mục</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;"><?php echo e($book->category->name ?? 'N/A'); ?></p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Nhà Xuất Bản</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e($book->publisher ?? 'Không xác định'); ?></p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Năm Xuất Bản</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e($book->publication_year ?? 'Không xác định'); ?></p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Ngôn Ngữ</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e($book->language ?? 'Không xác định'); ?></p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Số Trang</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e($book->pages ?? 'Không xác định'); ?> trang</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Phí Mượn</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e(number_format($book->borrow_fee ?? 0)); ?> đ</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Phí Quá Hạn / Ngày</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e(number_format($book->daily_late_fee ?? 5000)); ?> đ</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Nguồn Sách</p>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; color: #1f2937;"><?php echo e($book->source_name ?? 'Chưa cập nhật'); ?></p>
            </div>
        </div>

        <!-- Divider -->
        <div style="height: 1px; background: #e5e7eb; margin: 1.5rem 0;"></div>

        <!-- Description -->
        <?php if($book->description): ?>
            <div style="margin-bottom: 1.5rem;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1.2rem; color: #1f2937;">📖 Mô Tả</h3>
                <p style="margin: 0; color: #4b5563; line-height: 1.6; font-size: 0.95rem;"><?php echo e($book->description); ?></p>
            </div>
        <?php endif; ?>

        <!-- Availability Info -->
        <div style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(8, 145, 178, 0.05) 100%); border-left: 4px solid #06b6d4; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <h4 style="margin: 0 0 0.5rem 0; color: #0891b2; font-weight: 600;">ℹ️ Thông Tin Sẵn Có</h4>
            <p style="margin: 0; color: #1f2937; font-size: 0.95rem;">
                <strong><?php echo e($book->available_copies); ?></strong> bản sách đang có tại thư viện
                <?php if($book->total_borrowed > 0): ?>
                    | <strong><?php echo e($book->total_borrowed ?? 0); ?></strong> bản đang được mượn
                <?php endif; ?>
            </p>
        </div>

        <!-- Reservation Info -->
        <?php if($isBorrowed || $isReserved): ?>
            <div style="background: #e0f2fe; border-left: 4px solid #0284c7; padding: 1rem; border-radius: 8px;">
                <p style="margin: 0; color: #0c4a6e; font-size: 0.95rem;">
                    <?php if($isBorrowed): ?>
                        <strong>✓ Bạn đang mượn sách này.</strong> Vui lòng trả đúng hạn.
                    <?php elseif($isReserved): ?>
                        <strong>❤️ Bạn đã đặt sách này.</strong> Hãy lấy nó trước <?php echo e($userReservation->pickup_by->format('d/m/Y')); ?>.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Related Books (Optional - Future) -->
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <h3 style="margin: 0 0 0.75rem 0; font-size: 1.1rem; color: #1f2937;">📚 Các Sách Khác Của Tác Giả</h3>
            <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">
                <a href="/member/browse?search=<?php echo e(urlencode($book->author)); ?>" style="color: #0284c7; text-decoration: none;">
                    Xem tất cả sách của <?php echo e($book->author); ?> →
                </a>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/member/book-detail.blade.php ENDPATH**/ ?>