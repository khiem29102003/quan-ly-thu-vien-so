@extends('layout')

@section('title', 'Tạo Phiếu Mượn Mới')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/borrows" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 style="margin-bottom: 1.5rem;">📋 Tạo Phiếu Mượn Mới</h2>

    <div style="background: #fff7ed; border-left: 4px solid #fb923c; color: #9a3412; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1rem;">
        Hệ thống sẽ tự trừ phí mượn từ ví người dùng. Khi trả sách, phí quá hạn sẽ trừ tự động theo số ngày trễ.
    </div>

    <form action="/borrows" method="POST">
        @csrf

        <div class="form-group">
            <label for="user_id">Người Dùng *</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Chọn Người Dùng --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" data-wallet="{{ (int)($user->wallet_balance ?? 0) }}" data-debt="{{ (int)($user->outstanding_debt ?? 0) }}">
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="book_id">Sách *</label>
            <select name="book_id" id="book_id" required>
                <option value="">-- Chọn Sách --</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" data-fee="{{ (int)($book->borrow_fee ?? 0) }}" data-late="{{ (int)($book->daily_late_fee ?? 5000) }}" data-digital="{{ $book->is_digital ? 1 : 0 }}">
                        {{ $book->title }} - {{ $book->author }} (Còn: {{ $book->available_copies }})
                    </option>
                @endforeach
            </select>
            @error('book_id')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div id="borrowPreview" style="background: #f8fafc; border:1px solid #e2e8f0; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="margin:0.25rem 0;"><strong>Thời hạn mượn:</strong> 14 ngày</p>
            <p style="margin:0.25rem 0;"><strong>Phí mượn dự kiến:</strong> <span id="previewBorrowFee">0</span> ₫</p>
            <p style="margin:0.25rem 0;"><strong>Phí quá hạn/ngày:</strong> <span id="previewLateFee">5,000</span> ₫</p>
            <p style="margin:0.25rem 0;"><strong>Số dư ví người dùng:</strong> <span id="previewWallet">0</span> ₫</p>
            <p style="margin:0.25rem 0;"><strong>Nợ tồn hiện tại:</strong> <span id="previewDebt">0</span> ₫</p>
            <p id="previewMode" style="margin:0.4rem 0 0; color:#475569;"></p>
            <p id="previewWarning" style="display:none; margin:0.5rem 0 0; color:#b91c1c; font-weight:600;"></p>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button id="submitBorrowBtn" type="submit" class="btn btn-primary">✓ Tạo Phiếu Mượn</button>
            <a href="/borrows" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script>
(function () {
    const userSelect = document.getElementById('user_id');
    const bookSelect = document.getElementById('book_id');
    const submitBtn = document.getElementById('submitBorrowBtn');
    const warningEl = document.getElementById('previewWarning');
    const feeEl = document.getElementById('previewBorrowFee');
    const lateEl = document.getElementById('previewLateFee');
    const walletEl = document.getElementById('previewWallet');
    const debtEl = document.getElementById('previewDebt');
    const modeEl = document.getElementById('previewMode');

    const fmt = (n) => Number(n || 0).toLocaleString('vi-VN');

    function updatePreview() {
        const userOpt = userSelect.options[userSelect.selectedIndex];
        const bookOpt = bookSelect.options[bookSelect.selectedIndex];

        const wallet = Number(userOpt?.dataset?.wallet || 0);
        const debt = Number(userOpt?.dataset?.debt || 0);
        const fee = Number(bookOpt?.dataset?.fee || 0);
        const late = Number(bookOpt?.dataset?.late || 5000);
        const isDigital = Number(bookOpt?.dataset?.digital || 0) === 1;

        feeEl.textContent = fmt(fee);
        lateEl.textContent = fmt(late);
        walletEl.textContent = fmt(wallet);
        debtEl.textContent = fmt(debt);
        modeEl.textContent = isDigital ? 'Loại sách: Sách số (ebook)' : 'Loại sách: Sách vật lý';

        let warning = '';
        if (debt > 0) {
            warning = 'Người dùng đang có nợ tồn, cân nhắc thu nợ trước khi tạo phiếu.';
        }
        if (wallet < fee) {
            warning = 'Ví không đủ phí mượn, backend sẽ từ chối tạo phiếu.';
        }

        if (warning) {
            warningEl.style.display = 'block';
            warningEl.textContent = warning;
            submitBtn.disabled = wallet < fee;
        } else {
            warningEl.style.display = 'none';
            warningEl.textContent = '';
            submitBtn.disabled = false;
        }
    }

    userSelect.addEventListener('change', updatePreview);
    bookSelect.addEventListener('change', updatePreview);
    updatePreview();
})();
</script>
@endsection
