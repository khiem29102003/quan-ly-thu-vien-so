<?php $__env->startSection('title', 'Dashboard - Tài Khoản Thành Viên'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 style="margin: 0; font-size: 1.75rem;">👋 Chào <?php echo e(Auth::user()->name); ?></h2>
        <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Chào mừng trở lại thư viện của chúng tôi</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="/member/browse" class="btn btn-primary">
            <i class="fas fa-heart"></i> Duyệt & Đặt Sách
        </a>
        <a href="/member/profile" class="btn btn-secondary">
            <i class="fas fa-user"></i> Sửa Thông Tin
        </a>
    </div>
</div>

<?php if(!empty($stats['read_locked'])): ?>
    <div style="margin-bottom: 1rem; background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; padding: 0.9rem 1rem; border-radius: 8px;">
        Tài khoản đang có sách quá hạn. Quyền đọc sách số tạm bị khóa cho tới khi xử lý quá hạn.
    </div>
<?php endif; ?>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem;">
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.95) 0%, rgba(8, 145, 178, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">📚</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['active_borrows']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Đang Mượn</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">❤️</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['reservations']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Đặt Sách</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.95) 0%, rgba(217, 119, 6, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">⚠️</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['overdue_count']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Quá Hạn</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.95) 0%, rgba(2, 132, 199, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">📊</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;"><?php echo e($stats['total_borrowed']); ?></h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Tổng Mượn</p>
        </div>
    </div>

    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.95) 0%, rgba(79, 70, 229, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">💳</div>
            <h3 style="font-size: 1.2rem; margin: 0; color: white; font-weight: 700;"><?php echo e(number_format($stats['wallet_balance'] ?? 0)); ?>đ</h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Số Dư Ví</p>
        </div>
    </div>

    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">💸</div>
            <h3 style="font-size: 1.2rem; margin: 0; color: white; font-weight: 700;"><?php echo e(number_format($stats['outstanding_debt'] ?? 0)); ?>đ</h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Nợ Quá Hạn</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.25rem;">
    <!-- Active Borrows -->
    <div class="chart-container">
        <h3 class="chart-title">📖 Sách Đang Mượn</h3>
        <?php if($activeBorrows->count() > 0): ?>
            <div style="max-height: 320px; overflow-y: auto;">
                <table class="table" style="margin-bottom: 0; font-size: 0.9rem;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Tên Sách</th>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Hạn Trả</th>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Tình Trạng</th>
                            <th style="padding: 0.75rem 1rem; font-size: 0.8rem;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $activeBorrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="padding: 0.6rem 1rem;"><strong><?php echo e(Str::limit($borrow->book->title, 25)); ?></strong></td>
                                <td style="padding: 0.6rem 1rem; font-size: 0.85rem;"><?php echo e($borrow->due_date->format('d/m/Y')); ?></td>
                                <td style="padding: 0.6rem 1rem;">
                                    <?php if($borrow->due_date < now()): ?>
                                        <span style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.6rem; border-radius: 16px; font-size: 0.8rem; font-weight: 600;">⚠️ Quá Hạn</span>
                                    <?php else: ?>
                                        <span style="background: #d1fae5; color: #065f46; padding: 0.2rem 0.6rem; border-radius: 16px; font-size: 0.8rem; font-weight: 600;">✓ Còn Hạn</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 0.6rem 1rem;">
                                    <form action="<?php echo e(route('member.return-book', $borrow->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success" style="padding: 0.35rem 0.6rem; font-size: 0.75rem;" onclick="return confirm('Bạn muốn trả sách này ngay?')">Trả ngay</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 0.75rem; text-align: right;">
                <a href="<?php echo e(route('member.borrowed')); ?>" style="color: #0284c7; text-decoration: none; font-size: 0.9rem; font-weight: 600;">Xem toàn bộ sách đang mượn →</a>
            </div>
        <?php else: ?>
            <p style="color: #6b7280; font-size: 0.9rem; margin: 1rem 0;">Bạn chưa mượn sách nào</p>
        <?php endif; ?>
    </div>

    <!-- Reservations -->
    <div class="chart-container">
        <h3 class="chart-title">❤️ Yêu Cầu Mượn Gần Đây</h3>
        <?php if($reservations->count() > 0): ?>
            <div style="max-height: 320px; overflow-y: auto;">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="padding: 1rem; background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(8, 145, 178, 0.03) 100%); border-radius: 8px; border-left: 3px solid #06b6d4;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div style="flex: 1;">
                                    <p style="margin: 0; font-weight: 600; color: #1f2937;"><?php echo e(Str::limit($reservation->book->title, 30)); ?></p>
                                    <p style="margin: 0.25rem 0 0 0; font-size: 0.85rem; color: #6b7280;">Đặt: <?php echo e($reservation->reserved_at->format('d/m/Y')); ?></p>
                                </div>
                                <?php if($reservation->status === 'pending'): ?>
                                    <form action="/member/cancel-reservation/<?php echo e($reservation->id); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;" onclick="return confirm('Hủy đặt sách này?')">Hủy</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:0.5rem;">
                                <p style="margin: 0; font-size: 0.8rem; color: #0284c7;"><i class="fas fa-calendar"></i> Lấy trước: <?php echo e(optional($reservation->pickup_by)->format('d/m/Y')); ?></p>
                                <?php if($reservation->status === 'pending'): ?>
                                    <span style="background:#fef3c7;color:#92400e;padding:0.2rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Chờ duyệt</span>
                                <?php elseif($reservation->status === 'confirmed'): ?>
                                    <span style="background:#d1fae5;color:#065f46;padding:0.2rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Đã duyệt</span>
                                <?php else: ?>
                                    <span style="background:#fee2e2;color:#991b1b;padding:0.2rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Đã hủy/từ chối</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php else: ?>
            <p style="color: #6b7280; font-size: 0.9rem; margin: 1rem 0;">Bạn chưa có yêu cầu mượn nào</p>
        <?php endif; ?>
    </div>
</div>

<div class="chart-container" style="margin-top: 1.25rem;">
    <h3 class="chart-title">🧭 Hướng Dẫn Mượn - Trả</h3>
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap:0.75rem;">
        <div style="padding:0.8rem; border-radius:8px; background:#f8fafc; border:1px solid #e2e8f0;">
            <div style="font-weight:700; color:#0f172a;">1) Gửi yêu cầu mượn</div>
            <div style="font-size:0.86rem; color:#475569; margin-top:0.3rem;">Vào Duyệt & Đặt Sách, chọn sách rồi bấm Đặt Sách.</div>
        </div>
        <div style="padding:0.8rem; border-radius:8px; background:#f8fafc; border:1px solid #e2e8f0;">
            <div style="font-weight:700; color:#0f172a;">2) Chờ thủ thư duyệt</div>
            <div style="font-size:0.86rem; color:#475569; margin-top:0.3rem;">Yêu cầu hiển thị trạng thái Chờ duyệt/Đã duyệt ngay tại mục Yêu cầu mượn gần đây.</div>
        </div>
        <div style="padding:0.8rem; border-radius:8px; background:#f8fafc; border:1px solid #e2e8f0;">
            <div style="font-weight:700; color:#0f172a;">3) Theo dõi sách đang mượn</div>
            <div style="font-size:0.86rem; color:#475569; margin-top:0.3rem;">Vào mục Đang Mượn để xem hạn trả và thao tác đọc ebook (nếu có).</div>
        </div>
        <div style="padding:0.8rem; border-radius:8px; background:#f8fafc; border:1px solid #e2e8f0;">
            <div style="font-weight:700; color:#0f172a;">4) Trả sách</div>
            <div style="font-size:0.86rem; color:#475569; margin-top:0.3rem;">Bạn có thể bấm Trả ngay tại Dashboard, Đang Mượn hoặc Lịch sử mượn.</div>
        </div>
    </div>
</div>

<div class="chart-container" style="margin-top: 1.25rem;">
    <h3 class="chart-title">💳 Nạp Ví Bằng QR Code & Chuyển Khoản</h3>
    <p style="margin: 0 0 1.25rem 0; color: #475569; font-size: 0.9rem;">Điền số tiền bạn muốn nạp để hiển thị mã QR định danh. Khi chuyển khoản xong, vui lòng bấm Gửi Yêu Cầu để thủ thư duyệt số dư (thường mất 5-10 phút).</p>
    
    <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
        <!-- Form Nhập -->
        <div style="flex: 1; min-width: 300px;">
            <form action="<?php echo e(route('member.wallet.topup-request')); ?>" method="POST" id="topup-form" style="display: flex; flex-direction: column; gap: 1.25rem;">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="amount" style="display:block; margin-bottom:0.5rem; font-weight:600; color: #1e293b;">Số tiền cần nạp (VND) <span style="color:red;">*</span></label>
                    <input type="number" id="amount" name="amount" min="10000" max="50000000" step="1000" required placeholder="Nhập từ 10.000đ trở lên" class="form-control" onkeyup="updateQRCode()" onchange="updateQRCode()">
                </div>
                
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: #1e293b;">Chọn Phương Thức Thanh Toán</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <label id="lbl-bank" style="border: 2px solid #06b6d4; background: rgba(6,182,212,0.05); padding: 0.75rem; border-radius: 10px; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" name="payment_method" value="bank" checked onchange="updatePaymentMethods()" style="display: none;">
                            <i class="fas fa-university" style="color: #06b6d4; font-size: 1.2rem;"></i> <span style="font-weight: 600;">Ngân Hàng (QR)</span>
                        </label>
                        <label id="lbl-momo" style="border: 2px solid #e2e8f0; background: white; padding: 0.75rem; border-radius: 10px; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" name="payment_method" value="momo" onchange="updatePaymentMethods()" style="display: none;">
                            <i class="fas fa-wallet" style="color: #c2185b; font-size: 1.2rem;"></i> <span style="font-weight: 600; color: #475569;">Ví MoMo</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="note" style="display:block; margin-bottom:0.5rem; font-weight:600; color: #1e293b;">Ghi chú / Mã Giao Dịch</label>
                    <input type="text" id="note" name="note" class="form-control" maxlength="500" placeholder="Ghi chú giao dịch (tuỳ chọn)" value="NAP <?php echo e(Auth::user()->id); ?> BANK 0">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; font-size: 1.05rem;"><i class="fas fa-paper-plane"></i> XÁC NHẬN ĐÃ CHUYỂN & GỬI YÊU CẦU</button>
            </form>
        </div>

        <!-- Hiển thị QR -->
        <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; justify-content: center; align-items: center; background: #f8fafc; border-radius: 16px; padding: 2rem; border: 2px dashed #cbd5e1;">
            <div id="qr-container" style="text-align: center; width: 100%;">
                <p style="margin-bottom: 1rem; font-weight: 700; color: #1e293b; font-size: 1.1rem;"><i class="fas fa-qrcode" style="color:#06b6d4;"></i> Quét Mã VietQR (Tự Động Điền Tiền)</p>
                <div style="background: white; padding: 1rem; border-radius: 12px; box-shadow: var(--shadow-md); display: inline-block;">
                    <img id="qr-image" src="https://img.vietqr.io/image/<?php echo e($bankConfig['bank_bin']); ?>-<?php echo e($bankConfig['account_no']); ?>-compact2.png?amount=0&addInfo=NAP%20<?php echo e(Auth::id()); ?>&accountName=<?php echo e(urlencode($bankConfig['account_name'])); ?>" style="width: 220px; height: 220px; transition: all 0.3s ease; object-fit: contain;" alt="QR Code">
                </div>
                <div style="margin-top: 1.25rem; font-size: 0.95rem; color: #475569; background: white; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    Chủ tài khoản: <strong style="color: #06b6d4;"><?php echo e($bankConfig['account_name']); ?></strong><br>
                    Mã Ngân hàng (BIN): <strong><?php echo e($bankConfig['bank_bin']); ?></strong><br>
                    Số tài khoản: <strong style="font-size: 1.1rem; color: #0284c7;"><?php echo e($bankConfig['account_no']); ?></strong>
                </div>
            </div>

            <div id="momo-container" style="text-align: center; display: none; width: 100%;">
                <p style="margin-bottom: 1rem; font-weight: 700; color: #c2185b; font-size: 1.1rem;"><i class="fas fa-mobile-alt"></i> Chuyển tiền qua ứng dụng MoMo</p>
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow-md); display: inline-block;">
                    <i class="fas fa-wallet" style="font-size: 4rem; color: #c2185b; margin-bottom: 1rem;"></i>
                    <div style="font-size: 1.1rem; color: #334155;">
                        SĐT MoMo: <strong style="font-size: 1.4rem; color: #c2185b; display: block; margin: 0.5rem 0;"><?php echo e($momoConfig['phone']); ?></strong>
                        Người nhận: <strong><?php echo e($momoConfig['name']); ?></strong>
                    </div>
                </div>
                <p style="margin-top: 1.25rem; font-size: 0.9rem; color: #64748b;">* Vui lòng tự nhập đúng số tiền vào app MoMo và ghi chú mã hệ thống <strong style="color: #0284c7;">NAP <?php echo e(Auth::id()); ?></strong>.</p>
            </div>
        </div>
    </div>
    
    <script>
        function updatePaymentMethods() {
            var method = document.querySelector('input[name="payment_method"]:checked').value;
            var bankLbl = document.getElementById('lbl-bank');
            var momoLbl = document.getElementById('lbl-momo');
            var qrContainer = document.getElementById('qr-container');
            var momoContainer = document.getElementById('momo-container');

            if (method === 'bank') {
                bankLbl.style.border = '2px solid #06b6d4';
                bankLbl.style.background = 'rgba(6,182,212,0.05)';
                bankLbl.querySelector('span').style.color = '#1e293b';
                
                momoLbl.style.border = '2px solid #e2e8f0';
                momoLbl.style.background = 'white';
                momoLbl.querySelector('span').style.color = '#475569';
                
                qrContainer.style.display = 'block';
                momoContainer.style.display = 'none';
            } else {
                momoLbl.style.border = '2px solid #c2185b';
                momoLbl.style.background = 'rgba(194,24,91,0.05)';
                momoLbl.querySelector('span').style.color = '#1e293b';
                
                bankLbl.style.border = '2px solid #e2e8f0';
                bankLbl.style.background = 'white';
                bankLbl.querySelector('span').style.color = '#475569';
                
                qrContainer.style.display = 'none';
                momoContainer.style.display = 'block';
            }
            updateQRCode();
        }

        function updateQRCode() {
            var amount = document.getElementById('amount').value || 0;
            var method = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (method === 'bank') {
                var bankBin = '<?php echo e($bankConfig['bank_bin']); ?>';
                var accountNo = '<?php echo e($bankConfig['account_no']); ?>';
                var accountName = '<?php echo e($bankConfig['account_name']); ?>';
                var id = '<?php echo e(Auth::id()); ?>';
                var message = 'NAP ' + id;
                
                var qrImg = document.getElementById('qr-image');
                qrImg.style.opacity = '0.3';
                // compact2 template includes both account and amount
                qrImg.src = 'https://img.vietqr.io/image/' + bankBin + '-' + accountNo + '-compact2.png?amount=' + amount + '&addInfo=' + encodeURIComponent(message) + '&accountName=' + encodeURIComponent(accountName);
                
                setTimeout(function() { qrImg.style.opacity = '1'; }, 300); // UI smoother transition
            }
            
            // Xử lý auto update ghi chú tuỳ thuộc phương thức
            var notePrefix = 'NAP <?php echo e(Auth::id()); ?> ' + method.toUpperCase() + ' ' + amount;
            document.getElementById('note').value = notePrefix;
        }

        // Khởi tạo ban đầu
        document.addEventListener('DOMContentLoaded', function() {
            updatePaymentMethods();
        });
    </script>

    <?php if(!empty($topupRequests) && $topupRequests->count() > 0): ?>
        <div style="margin-top: 1rem; border-top: 1px solid #e2e8f0; padding-top: 0.75rem;">
            <p style="margin: 0 0 0.5rem 0; color: #334155; font-weight: 600;">Yêu cầu gần đây</p>
            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <?php $__currentLoopData = $topupRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requestLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding:0.6rem 0.75rem; border-radius:8px; background:#f8fafc; border:1px solid #e2e8f0; display:flex; justify-content:space-between; gap:0.75rem; align-items:center;">
                        <div>
                            <div style="font-size:0.9rem; color:#0f172a; font-weight:600;"><?php echo e(number_format((int) data_get($requestLog->properties, 'amount', 0))); ?> VND</div>
                            <div style="font-size:0.78rem; color:#64748b;"><?php echo e($requestLog->created_at->format('d/m/Y H:i')); ?></div>
                        </div>
                        <div>
                            <?php $status = data_get($requestLog->properties, 'status', $requestLog->event === 'approved' ? 'approved' : 'pending'); ?>
                            <?php if($status === 'approved' || $requestLog->event === 'approved'): ?>
                                <span style="background:#d1fae5;color:#065f46;padding:0.25rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:600;">Đã duyệt</span>
                            <?php else: ?>
                                <span style="background:#fef3c7;color:#92400e;padding:0.25rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:600;">Chờ duyệt</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="chart-container" style="margin-top: 1.25rem;">
    <h3 class="chart-title">⚡ Thao Tác Nhanh</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="/member/browse" class="btn btn-primary" style="padding: 1rem; text-align: center; text-decoration: none;">
            <i class="fas fa-search" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
            Tìm & Đặt Sách
        </a>
        <a href="/member/history" class="btn btn-info" style="padding: 1rem; text-align: center; text-decoration: none;">
            <i class="fas fa-history" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
            Lịch Sử Mượn
        </a>
        <a href="/member/profile" class="btn btn-secondary" style="padding: 1rem; text-align: center; text-decoration: none;">
            <i class="fas fa-cog" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
            Cài Đặt Tài Khoản
        </a>
        <a href="/books" class="btn btn-primary" style="padding: 1rem; text-align: center; text-decoration: none;">
            <i class="fas fa-star" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
            Sách Phổ Biến
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/member/dashboard.blade.php ENDPATH**/ ?>