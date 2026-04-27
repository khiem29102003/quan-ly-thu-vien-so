<?php $__env->startSection('title', 'Dashboard - Quản Lý Thư Viện'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="margin: 0; font-size: 1.75rem;">📊 Bảng Điều Khiển</h2>
    <?php if(auth()->check() && in_array(auth()->user()->role, ['admin', 'librarian'])): ?>
    <div style="display: flex; gap: 0.5rem;">
        <a href="/books" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Quản Lý Sách</a>
        <a href="/borrows" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Phiếu Mượn</a>
    </div>
    <?php endif; ?>
</div>

<!-- Premium Compact Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem;">
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.95) 0%, rgba(8, 145, 178, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">📚</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['total_books']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Sách</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">✅</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['available_books']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Sẵn Có</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.95) 0%, rgba(124, 58, 237, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">👥</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['total_users']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Người Dùng</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.95) 0%, rgba(217, 119, 6, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">📋</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['active_borrows']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Đang Mượn</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">⚠️</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['overdue_borrows']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Quá Hạn</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.95) 0%, rgba(2, 132, 199, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">📊</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['total_borrows']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Mượn</p>
        </div>
    </div>
</div>

<?php if(auth()->check() && in_array(auth()->user()->role, ['admin', 'librarian'])): ?>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
    <div class="chart-container">
        <h3 class="chart-title">🔔 Đặt Sách Chờ Xử Lý (<?php echo e($pendingReservations->count()); ?>)</h3>
        <?php if($pendingReservations->count() > 0): ?>
            <div style="display:flex; flex-direction:column; gap:0.6rem; max-height:300px; overflow:auto;">
                <?php $__currentLoopData = $pendingReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding:0.7rem 0.8rem; border-radius:8px; background:#f0f9ff; border-left:3px solid #0284c7;">
                        <div style="font-weight:600; color:#0f172a;"><?php echo e($reservation->book->title ?? 'N/A'); ?></div>
                        <div style="font-size:0.85rem; color:#475569; margin-top:0.25rem;">Thành viên: <?php echo e($reservation->user->name ?? 'N/A'); ?></div>
                        <div style="font-size:0.8rem; color:#64748b; margin-top:0.2rem;">Đặt lúc: <?php echo e(optional($reservation->reserved_at)->format('d/m/Y H:i')); ?></div>
                        <div style="display:flex; gap:0.45rem; margin-top:0.45rem;">
                            <form action="<?php echo e(route('borrows.reservations.approve', $reservation->id)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success" style="padding:0.28rem 0.55rem; font-size:0.75rem;" onclick="return confirm('Duyệt yêu cầu này và tạo phiếu mượn?')">Duyệt</button>
                            </form>
                            <form action="<?php echo e(route('borrows.reservations.reject', $reservation->id)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger" style="padding:0.28rem 0.55rem; font-size:0.75rem;" onclick="return confirm('Từ chối yêu cầu này?')">Từ chối</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div style="margin-top:0.65rem; text-align:right;">
                <a href="<?php echo e(route('borrows.reservations')); ?>" style="color:#0284c7; text-decoration:none; font-size:0.88rem; font-weight:600;">Xem toàn bộ yêu cầu mượn →</a>
            </div>
        <?php else: ?>
            <p style="color:#6b7280;">Hiện không có yêu cầu đặt sách mới.</p>
        <?php endif; ?>
    </div>

    <div class="chart-container">
        <h3 class="chart-title">💳 Yêu Cầu Nạp Ví (<?php echo e($pendingTopupRequests->count()); ?>)</h3>
        <?php if($pendingTopupRequests->count() > 0): ?>
            <div style="display:flex; flex-direction:column; gap:0.7rem; max-height:300px; overflow:auto;">
                <?php $__currentLoopData = $pendingTopupRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requestLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding:0.8rem; border-radius:8px; background:#f8fafc; border-left:3px solid #0ea5e9;">
                        <div style="display:flex; justify-content:space-between; gap:0.5rem; align-items:flex-start;">
                            <div>
                                <div style="font-weight:600; color:#0f172a;"><?php echo e($requestLog->causer->name ?? 'Thanh vien'); ?></div>
                                <div style="font-size:0.85rem; color:#475569; margin-top:0.2rem;">Số tiền: <?php echo e(number_format((int) data_get($requestLog->properties, 'amount', 0))); ?> VND</div>
                                <?php if(data_get($requestLog->properties, 'note')): ?>
                                    <div style="font-size:0.8rem; color:#64748b; margin-top:0.2rem;">Ghi chú: <?php echo e(data_get($requestLog->properties, 'note')); ?></div>
                                <?php endif; ?>
                            </div>
                            <form action="<?php echo e(route('wallet-topups.approve', $requestLog->id)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success" style="padding:0.35rem 0.65rem; font-size:0.75rem;" onclick="return confirm('Duyệt yêu cầu nạp ví này?')">Duyệt</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p style="color:#6b7280;">Hiện không có yêu cầu nạp ví mới.</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.25rem; margin-top: 1.5rem;">
    <div class="chart-container">
        <h3 class="chart-title">📈 Thống Kê Mượn Sách Theo Tháng</h3>
        <canvas id="borrowsByMonthChart" style="max-height: 280px;"></canvas>
    </div>
    <div class="chart-container">
        <h3 class="chart-title">📊 Phân Bố Sách Theo Danh Mục</h3>
        <canvas id="booksByCategoryChart" style="max-height: 280px;"></canvas>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.25rem; margin-top: 1.5rem;">
    <div class="chart-container">
        <h3 class="chart-title">📖 Sách Phổ Biến Nhất</h3>
        <?php if($popular_books->count() > 0): ?>
            <div style="max-height: 320px; overflow-y: auto;">
                <table class="table" style="margin-bottom: 0;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Tên Sách</th>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Tác Giả</th>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem; text-align: center; width: 60px;">Đánh Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $popular_books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="font-size: 0.9rem;">
                                <td style="padding: 0.6rem 1rem;"><strong><?php echo e(Str::limit($book->title, 25)); ?></strong></td>
                                <td style="padding: 0.6rem 1rem; font-size: 0.85rem;"><?php echo e(Str::limit($book->author, 18)); ?></td>
                                <td style="padding: 0.6rem 1rem; text-align: center; color: #f59e0b; font-weight: 600;">⭐ <?php echo e($book->rating); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="color: #6b7280; font-size: 0.9rem;">Chưa có dữ liệu</p>
        <?php endif; ?>
    </div>

    <div class="chart-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <h3 class="chart-title" style="margin-bottom: 0;">🏷️ Top Danh Mục</h3>
        </div>
        <?php if($categories->count() > 0): ?>
            <div style="display: grid; grid-template-columns: 1fr; gap: 0.5rem; max-height: 320px; overflow-y: auto;">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.8rem; background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(8, 145, 178, 0.03) 100%); border-radius: 8px; border-left: 3px solid #06b6d4; font-size: 0.9rem;">
                        <span style="font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-right: 0.5rem;" title="<?php echo e($category->name); ?>"><?php echo e(Str::limit($category->name, 20)); ?></span>
                        <span style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; padding: 0.2rem 0.6rem; border-radius: 16px; font-size: 0.8rem; font-weight: 700; flex-shrink: 0;"><?php echo e($category->books_count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p style="color: #6b7280; font-size: 0.9rem;">Chưa có danh mục</p>
        <?php endif; ?>
    </div>
</div>

<div class="chart-container" style="margin-top: 1.25rem;">
    <h3 class="chart-title">📝 Lịch Sử Mượn Gần Đây</h3>
    <?php if($recent_borrows->count() > 0): ?>
        <div style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
            <table class="table" style="margin-bottom: 0; font-size: 0.9rem;">
                <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                    <tr>
                        <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Người Dùng</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Sách</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Ngày Mượn</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Hạn Trả</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $recent_borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="font-size: 0.9rem;">
                            <td style="padding: 0.6rem 1rem;"><?php echo e(Str::limit($borrow->user->name, 20)); ?></td>
                            <td style="padding: 0.6rem 1rem;"><?php echo e(Str::limit($borrow->book->title, 25)); ?></td>
                            <td style="padding: 0.6rem 1rem; font-size: 0.85rem;"><?php echo e($borrow->borrowed_at->format('d/m/Y')); ?></td>
                            <td style="padding: 0.6rem 1rem; font-size: 0.85rem;"><?php echo e($borrow->due_date->format('d/m/Y')); ?></td>
                            <td style="padding: 0.6rem 1rem;">
                                <span style="padding: 0.2rem 0.6rem; border-radius: 16px; font-size: 0.8rem; font-weight: 600;
                                    <?php if($borrow->status === 'borrowed'): ?> background: #dbeafe; color: #0c2d6b;
                                    <?php elseif($borrow->status === 'returned'): ?> background: #d1fae5; color: #065f46;
                                    <?php else: ?> background: #fee2e2; color: #991b1b;
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($borrow->status)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="color: #6b7280; font-size: 0.9rem;">Chưa có phiếu mượn</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<style>
    .chart-container {
        position: relative;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.98) 100%);
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.12), inset 0 0 1px rgba(255, 255, 255, 0.5);
        padding: 1.25rem;
        border: 1px solid rgba(6, 182, 212, 0.12);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        animation: fadeInUp 0.7s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .chart-container:hover {
        box-shadow: 0 16px 48px rgba(6, 182, 212, 0.18), inset 0 0 2px rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.25);
        transform: translateY(-4px);
    }
    
    .chart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.03) 0%, rgba(34, 211, 238, 0.02) 100%);
        pointer-events: none;
        transition: opacity 0.3s ease;
        opacity: 0;
    }
    
    .chart-container:hover::before {
        opacity: 1;
    }
    
    .chart-title {
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 0.875rem;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    canvas {
        filter: drop-shadow(0 0 0 transparent);
        transition: filter 0.3s ease, opacity 0.3s ease;
        will-change: filter;
    }
    
    .chart-container:hover canvas {
        filter: drop-shadow(0 3px 6px rgba(6, 182, 212, 0.15));
    }
    
    .chart-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50px;
        height: 50px;
        border: 3px solid rgba(6, 182, 212, 0.1);
        border-top-color: #06b6d4;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 10;
    }
    
    @keyframes spin {
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>

<script>
    // Register plugins
    Chart.register();

    // Color palette Cyan Neon
    const cyanGradient = ['#06b6d4', '#0891b2', '#22d3ee'];
    const colorPalette = [
        { bg: 'rgba(6, 182, 212, 0.8)', border: '#06b6d4', hover: 'rgba(6, 182, 212, 1)' },
        { bg: 'rgba(8, 145, 178, 0.8)', border: '#0891b2', hover: 'rgba(8, 145, 178, 1)' },
        { bg: 'rgba(34, 211, 238, 0.8)', border: '#22d3ee', hover: 'rgba(34, 211, 238, 1)' },
        { bg: 'rgba(16, 185, 129, 0.8)', border: '#10b981', hover: 'rgba(16, 185, 129, 1)' },
        { bg: 'rgba(139, 92, 246, 0.8)', border: '#8b5cf6', hover: 'rgba(139, 92, 246, 1)' },
        { bg: 'rgba(245, 158, 11, 0.8)', border: '#f59e0b', hover: 'rgba(245, 158, 11, 1)' },
        { bg: 'rgba(239, 68, 68, 0.8)', border: '#ef4444', hover: 'rgba(239, 68, 68, 1)' },
        { bg: 'rgba(59, 130, 246, 0.8)', border: '#3b82f6', hover: 'rgba(59, 130, 246, 1)' },
        { bg: 'rgba(236, 72, 153, 0.8)', border: '#ec4899', hover: 'rgba(236, 72, 153, 1)' },
        { bg: 'rgba(20, 184, 166, 0.8)', border: '#14b8a6', hover: 'rgba(20, 184, 166, 1)' }
    ];
    
    // Chart default options
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.color = '#6b7280';
    
    // Biểu đồ cột: Mượn sách theo tháng
    const ctxMonth = document.getElementById('borrowsByMonthChart').getContext('2d');
    const borrowsByMonth = <?php echo json_encode($borrowsByMonth, 15, 512) ?>;
    const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
    const barData = Object.values(borrowsByMonth);
    const maxValue = Math.max(...barData, 1);
    
    // Create gradient
    const gradientMonth = ctxMonth.createLinearGradient(0, 0, 0, 350);
    gradientMonth.addColorStop(0, 'rgba(6, 182, 212, 0.95)');
    gradientMonth.addColorStop(0.5, 'rgba(8, 145, 178, 0.7)');
    gradientMonth.addColorStop(1, 'rgba(8, 145, 178, 0.05)');
    
    const chartMonth = new Chart(ctxMonth, {
        type: 'bar',
        data: {
            labels: Object.keys(borrowsByMonth).map(m => monthNames[m - 1]),
            datasets: [{
                label: 'Số lượt mượn sách',
                data: barData,
                backgroundColor: gradientMonth,
                borderColor: '#06b6d4',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(6, 182, 212, 1)',
                hoverBorderColor: '#0284c7',
                hoverBorderWidth: 3,
                hoverShadowColor: 'rgba(6, 182, 212, 0.5)',
                barThickness: 'flex',
                barPercentage: 0.75,
                categoryPercentage: 0.85
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart',
                delay: (ctx) => {
                    let delay = 0;
                    if (ctx.type === 'data') {
                        delay = ctx.dataIndex * 80;
                    }
                    return delay;
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12, weight: '600' },
                        color: '#374151',
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.96)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    displayColors: true,
                    borderColor: '#06b6d4',
                    borderWidth: 2,
                    borderRadius: 8,
                    titleColor: '#22d3ee',
                    bodyColor: '#e2e8f0',
                    boxPadding: 8,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    caretPadding: 8,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y + ' lần';
                        },
                        afterLabel: function(context) {
                            const percentage = ((context.parsed.y / maxValue) * 100).toFixed(1);
                            return ' (' + percentage + '%)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: maxValue + 1,
                    ticks: {
                        stepSize: 1,
                        font: { size: 11, weight: '600' },
                        color: '#6b7280',
                        padding: 6
                    },
                    grid: {
                        color: 'rgba(6, 182, 212, 0.05)',
                        borderColor: 'rgba(6, 182, 212, 0.1)',
                        lineWidth: 1,
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11, weight: '500' },
                        color: '#6b7280',
                        padding: 6
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });

    // Biểu đồ tròn: Sách theo danh mục
    const ctxCategory = document.getElementById('booksByCategoryChart').getContext('2d');
    const booksByCategory = <?php echo json_encode($booksByCategory, 15, 512) ?>;
    const categoryValues = Object.values(booksByCategory);
    const totalBooks = categoryValues.reduce((a, b) => a + b, 0);
    
    const pieColors = colorPalette.slice(0, Object.keys(booksByCategory).length).map(c => c.bg);
    const pieBorders = colorPalette.slice(0, Object.keys(booksByCategory).length).map(c => c.border);
    
    const chartCategory = new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: Object.keys(booksByCategory),
            datasets: [{
                data: categoryValues,
                backgroundColor: pieColors,
                borderColor: '#ffffff',
                borderWidth: 2.5,
                hoverOffset: 10,
                hoverBorderWidth: 3,
                hoverBackgroundColor: colorPalette.slice(0, Object.keys(booksByCategory).length).map(c => c.hover)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'nearest'
            },
            animation: {
                duration: 1800,
                easing: 'easeInOutQuart',
                animateRotate: true,
                animateScale: false,
                delay: (ctx) => {
                    return ctx.dataIndex * 120;
                }
            },
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: { size: 11, weight: '600' },
                        color: '#374151',
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 9
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.96)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    displayColors: true,
                    borderColor: '#06b6d4',
                    borderWidth: 2,
                    borderRadius: 8,
                    titleColor: '#22d3ee',
                    bodyColor: '#e2e8f0',
                    boxPadding: 8,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    caretPadding: 8,
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const percentage = ((value / totalBooks) * 100).toFixed(1);
                            return ' ' + value + ' cuốn (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Add hover effect to chart containers
    document.querySelectorAll('.chart-container').forEach(container => {
        container.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        container.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add chart update animation on window resize
    window.addEventListener('resize', () => {
        chartMonth.resize();
        chartCategory.resize();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/dashboard.blade.php ENDPATH**/ ?>