@extends('layout')

@section('title', 'Cấu Hình Thanh Toán & Hệ Thống')

@section('content')
<div class="chart-container" style="max-width: 800px; margin: 0 auto; margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">
        <div>
            <h2 style="margin: 0; color: #1e293b; font-size: 1.5rem;"><i class="fas fa-cog" style="color: #06b6d4;"></i> Cấu Hình Thanh Toán</h2>
            <p style="margin: 0.5rem 0 0 0; color: #64748b; font-size: 0.95rem;">Quản lý thông tin tài khoản thụ hưởng khi Độc giả nạp tiền.</p>
        </div>
    </div>


    <form action="{{ url('/admin/settings') }}" method="POST">
        @csrf
        
        <!-- Cấu hình Ngân Hàng VietQR -->
        <h4 style="margin: 0 0 1rem 0; color: #1e293b; font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-university" style="color: #06b6d4;"></i> Thông Tin VietQR (Bank)
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 2rem;">
            <!-- Cột Form Nhập -->
            <div style="flex: 1; min-width: 300px;">
                <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: #475569;">BIN Ngân Hàng (Mã Ngân Hàng)</label>
                        <input type="text" id="bank_bin" name="bank_bin" class="form-control" value="{{ $bankConfig['bank_bin'] ?? '' }}" required placeholder="VD: 970436 (Vietcombank)" onkeyup="previewQR()">
                        <small style="color: #94a3b8; font-size: 0.8rem; margin-top: 0.25rem; display: block;">Tra cứu BIN: 970436=VCB, 970422=MB...</small>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: #475569;">Số Tài Khoản</label>
                        <input type="text" id="account_no" name="account_no" class="form-control" value="{{ $bankConfig['account_no'] ?? '' }}" required placeholder="Nhập đúng số" onkeyup="previewQR()">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: #475569;">Tên Chủ Tài Khoản</label>
                        <input type="text" id="account_name" name="account_name" class="form-control" value="{{ $bankConfig['account_name'] ?? '' }}" required placeholder="VIẾT HOA KHÔNG DẤU" onkeyup="previewQR()">
                    </div>
                </div>
            </div>

            <!-- Cột Xem Trước (Preview) -->
            <div style="width: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 1.5rem;">
                <p style="margin: 0 0 1rem 0; font-weight: 600; color: #0284c7; font-size: 0.95rem; text-align: center;"><i class="fas fa-magic"></i> Xem Trước Mã QR</p>
                <div style="background: white; padding: 0.5rem; border-radius: 8px; box-shadow: var(--shadow-sm);">
                    <img id="qr-preview" src="https://img.vietqr.io/image/{{ $bankConfig['bank_bin'] ?? '970436' }}-{{ $bankConfig['account_no'] ?? '0987654321' }}-compact2.png?amount=50000&addInfo=NAP%201&accountName={{ urlencode($bankConfig['account_name'] ?? 'THU VIEN') }}" style="width: 100%; height: auto; transition: all 0.3s;" alt="Preview QR">
                </div>
                <small style="text-align: center; color: #64748b; font-size: 0.8rem; margin-top: 1rem; line-height: 1.4;">
                    <strong>Không cần upload ảnh!</strong><br>
                    Hệ thống sẽ dùng API của VietQR để <strong>TỰ ĐỘNG VẼ RA</strong> mã QR chuẩn 100% cực kỳ chuyên nghiệp và tiện dụng cho từng Độc giả dựa trên Số Tài Khoản của bạn.
                </small>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 2rem 0;">

        <!-- Cấu hình MoMo -->
        <h4 style="margin: 0 0 1rem 0; color: #1e293b; font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-wallet" style="color: #c2185b;"></i> Thông Tin MoMo
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
            <div>
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: #475569;">Số Điện Thoại MoMo</label>
                <input type="text" name="momo_phone" class="form-control" value="{{ $momoConfig['phone'] ?? '' }}" required placeholder="Nhập SDT MoMo">
            </div>
            <div>
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: #475569;">Tên Chủ MoMo</label>
                <input type="text" name="momo_name" class="form-control" value="{{ $momoConfig['name'] ?? '' }}" required placeholder="Nguyễn Văn A">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.05rem;">
                <i class="fas fa-save"></i> LƯU THAY ĐỔI
            </button>
        </div>
    </form>
</div>

<script>
    function previewQR() {
        var bankBin = document.getElementById('bank_bin').value || '970436';
        var accountNo = document.getElementById('account_no').value || '0987654321';
        var accountName = document.getElementById('account_name').value || 'THU VIEN';
        
        var qrImg = document.getElementById('qr-preview');
        qrImg.style.opacity = '0.3';
        qrImg.src = 'https://img.vietqr.io/image/' + bankBin + '-' + accountNo + '-compact2.png?amount=50000&addInfo=NAP%201&accountName=' + encodeURIComponent(accountName);
        
        setTimeout(function() { qrImg.style.opacity = '1'; }, 300);
    }
</script>
@endsection
