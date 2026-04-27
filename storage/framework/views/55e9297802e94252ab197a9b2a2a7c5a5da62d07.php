<?php $__env->startSection('title', 'Đọc Sách Số'); ?>

<?php $__env->startSection('content'); ?>
<?php
    // VIP condition: wallet balance >= 1M
    $isVIP = auth()->user()->wallet_balance >= 1000000;
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; gap: 0.75rem; flex-wrap: wrap;">
    <div>
        <h2 style="margin: 0;">📖 Đọc Sách Số <?php echo $isVIP ? '<span class="badge bg-warning" style="color:#000; background:linear-gradient(135deg, #fcd34d, #f59e0b);">TÀI KHOẢN VIP</span>' : ''; ?></h2>
        <p style="margin: 0.35rem 0 0; color: #6b7280;"><?php echo e($borrow->book->title); ?> - <?php echo e($borrow->book->author); ?></p>
    </div>
    <a href="/member/history" class="btn btn-secondary">Quay lại lịch sử</a>
</div>

<!-- Load PDF.js from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
</script>

<style>
    /* Reader Layout */
    .reader-container {
        display: flex;
        flex-direction: column;
        background: #f1f5f9;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        position: relative;
    }
    
    .reader-container.dark-mode {
        background: #0f172a;
        border-color: #1e293b;
    }

    /* Progress Bar */
    .progress-container {
        width: 100%;
        height: 4px;
        background: #e2e8f0;
        position: absolute;
        top: 0; left: 0; z-index: 15;
    }
    .dark-mode .progress-container {
        background: #334155;
    }
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #3b82f6);
        width: 0%;
        transition: width 0.3s ease;
        box-shadow: 0 0 8px rgba(6, 182, 212, 0.6);
    }

    /* Toolbar */
    .reader-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1.25rem;
        padding-top: 1rem;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
        gap: 1rem;
        flex-wrap: wrap;
        z-index: 10;
        transition: all 0.3s ease;
    }

    .dark-mode .reader-toolbar {
        background: rgba(30, 41, 59, 0.85);
        border-color: #334155;
        color: #e2e8f0;
    }

    .toolbar-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .reader-btn {
        background: white;
        border: 1px solid #cbd5e1;
        color: #334155;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .reader-btn:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }
    
    .dark-mode .reader-btn {
        background: #1e293b;
        border-color: #475569;
        color: #cbd5e1;
    }

    .dark-mode .reader-btn:hover {
        background: #334155;
    }

    .vip-lock {
        opacity: 0.6;
    }
    .vip-lock i.fa-lock {
        color: #f59e0b;
        font-size: 0.8rem;
        margin-left: 0.2rem;
    }

    .page-input {
        width: 50px;
        text-align: center;
        padding: 0.4rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-weight: 700;
        color: #0f172a;
    }

    .dark-mode .page-input {
        background: #1e293b;
        border-color: #475569;
        color: #f8fafc;
    }

    .canvas-wrapper {
        height: 75vh;
        overflow: auto;
        display: flex;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
    }

    #pdf-render {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-radius: 2px;
        transition: transform 0.2s ease-out;
    }
    
    .dark-mode #pdf-render {
        filter: invert(90%) hue-rotate(180deg) brightness(85%) contrast(85%);
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    /* Watermark */
    .watermark-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 20;
    }
    
    .watermark-text {
        position: absolute;
        inset: -50%;
        transform: rotate(-30deg);
        display: flex;
        flex-wrap: wrap;
        align-content: space-around;
        justify-content: space-around;
        color: rgba(15, 23, 42, 0.08);
        font-weight: 700;
        font-size: 1.2rem;
        user-select: none;
    }

    .dark-mode .watermark-text {
        color: rgba(226, 232, 240, 0.06);
    }

    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 30;
        backdrop-filter: blur(4px);
    }

    .dark-mode .loading-overlay {
        background: rgba(15,23,42,0.8);
        color: white;
    }

    /* Bookmark Sidebar */
    .bookmark-sidebar {
        position: absolute;
        top: 0; right: -300px;
        width: 300px; height: 100%;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        box-shadow: -5px 0 25px rgba(0,0,0,0.05);
        z-index: 25;
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        border-left: 1px solid #e2e8f0;
    }
    .dark-mode .bookmark-sidebar {
        background: rgba(15,23,42,0.95);
        border-color: #334155;
    }
    .bookmark-sidebar.open {
        right: 0;
    }

    .bookmark-item {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .dark-mode .bookmark-item { border-color: #1e293b; color: #cbd5e1; }
    .bookmark-item:hover { background: #f8fafc; }
    .dark-mode .bookmark-item:hover { background: #334155; }

    .del-bookmark { color: #ef4444; cursor: pointer; }

    /* Custom Toast VIP */
    #vip-toast {
        position: fixed;
        bottom: -100px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 999px;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
        z-index: 9999;
        transition: bottom 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    #vip-toast.show {
        bottom: 2rem;
    }
</style>

<?php if(!$isVIP): ?>
<div style="background: #fff7ed; border-left: 4px solid #f97316; color: #9a3412; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
    <div>
        <strong><i class="fas fa-exclamation-triangle"></i> Dành cho Tài Khoản Thường:</strong> Các tiện ích cao cấp như Lưu tiến độ, Chế độ Ban Đêm, Xoay trang và Đánh dấu Bookmark hiện đang khoá.
    </div>
    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-warning btn-sm" style="white-space: nowrap;"><i class="fas fa-gem"></i> Nạp Ví Lên 1 Triệu</a>
</div>
<?php endif; ?>

<div class="reader-container" id="reader-container">
    <div class="progress-container">
        <div class="progress-bar" id="progress-bar"></div>
    </div>

    <!-- Cảnh báo Load -->
    <div class="loading-overlay" id="loading-overlay">
        <i class="fas fa-spinner fa-spin fa-3x" style="color: #06b6d4; margin-bottom: 1rem;"></i>
        <h3 style="margin: 0;">Đang xử lý tài liệu...</h3>
        <p style="color: #64748b; margin-top: 0.5rem;" id="loading-text">Tự động tối ưu hoá ảnh trang</p>
    </div>

    <!-- Thanh Công Cụ -->
    <div class="reader-toolbar">
        <div class="toolbar-group">
            <button class="reader-btn" id="prev-page"><i class="fas fa-chevron-left"></i></button>
            <div style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                Trang <input type="number" id="page-num" class="page-input" value="1" min="1"> / <span id="page-count">--</span>
            </div>
            <button class="reader-btn" id="next-page"><i class="fas fa-chevron-right"></i></button>
        </div>

        <div class="toolbar-group">
            <button class="reader-btn" id="zoom-out" title="Thu Nhỏ"><i class="fas fa-search-minus"></i></button>
            <span style="font-weight: 600; font-size: 0.9rem; min-width: 50px; text-align: center;" id="zoom-val">100%</span>
            <button class="reader-btn" id="zoom-in" title="Phóng To"><i class="fas fa-search-plus"></i></button>
        </div>

        <div class="toolbar-group">
            <button class="reader-btn <?php echo e(!$isVIP ? 'vip-lock' : ''); ?>" id="btn-rotate" title="Xoay dọc/ngang">
                <i class="fas fa-sync-alt"></i><span class="d-none d-md-inline"> Xoay</span>
                <?php if(!$isVIP): ?><i class="fas fa-lock"></i><?php endif; ?>
            </button>
            <button class="reader-btn <?php echo e(!$isVIP ? 'vip-lock' : ''); ?>" id="btn-bookmark" title="Đánh dấu trang hiện tại">
                <i class="fas fa-bookmark"></i>
                <?php if(!$isVIP): ?><i class="fas fa-lock"></i><?php endif; ?>
            </button>
            <button class="reader-btn <?php echo e(!$isVIP ? 'vip-lock' : ''); ?>" id="toggle-sidebar" title="Danh sách trang đánh dấu">
                <i class="fas fa-list-ul"></i>
                <?php if(!$isVIP): ?><i class="fas fa-lock"></i><?php endif; ?>
            </button>
            <button class="reader-btn <?php echo e(!$isVIP ? 'vip-lock' : ''); ?>" id="toggle-dark" title="Chế độ ban đêm">
                <i class="fas fa-moon"></i><span class="d-none d-md-inline"> Ban Đêm</span>
                <?php if(!$isVIP): ?><i class="fas fa-lock"></i><?php endif; ?>
            </button>
            <button class="reader-btn" id="toggle-fullscreen" title="Toàn Màn Hình"><i class="fas fa-expand"></i></button>
        </div>
    </div>

    <!-- Bookmark Sidebar -->
    <div class="bookmark-sidebar" id="bookmark-sidebar">
        <div style="padding: 1rem; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; color: #0284c7;"><i class="fas fa-bookmark"></i> Trang Đã Lưu</h4>
            <i class="fas fa-times" id="close-sidebar" style="cursor: pointer; color: #64748b;"></i>
        </div>
        <div style="flex: 1; overflow-y: auto;" id="bookmark-list">
            <!-- Items -> JS -->
        </div>
    </div>

    <!-- Khối Đọc (Canvas) -->
    <div class="canvas-wrapper" id="canvas-wrapper">
        <canvas id="pdf-render"></canvas>
        
        <!-- Watermark -->
        <div class="watermark-overlay">
            <div class="watermark-text">
                <?php for($i = 0; $i < 30; $i++): ?>
                    <span><?php echo e(auth()->user()->email); ?> | TÀI LIỆU MẬT</span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<div id="vip-toast"><i class="fas fa-crown"></i> Tính năng Thượng Lưu (VIP). Cần số dư Ví từ 1.000.000đ!</div>

<script>
    // Security preventions
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('copy', e => e.preventDefault());
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && ['c', 'p', 's', 'u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
        }
    });

    const isVIP = <?php echo e($isVIP ? 'true' : 'false'); ?>;
    const bookId = '<?php echo e($borrow->book_id); ?>';
    const userId = '<?php echo e(auth()->id()); ?>';
    const CACHE_CHECKPOINT = `pdf_checkpoint_u${userId}_b${bookId}`;
    const CACHE_BOOKMARKS = `pdf_bookmarks_u${userId}_b${bookId}`;
    
    const url = '<?php echo e(route('member.read-book.stream', ['borrowId' => $borrow->id])); ?>';

    let pdfDoc = null,
        pageNum = 1,
        pageIsRendering = false,
        pageNumIsPending = null,
        scale = 1.3,
        rotation = 0,
        bookmarks = [];

    // Load bookmarks if VIP
    if (isVIP) {
        let savedBms = localStorage.getItem(CACHE_BOOKMARKS);
        if (savedBms) bookmarks = JSON.parse(savedBms);
    }

    const canvas = document.getElementById('pdf-render'),
          ctx = canvas.getContext('2d'),
          progressBar = document.getElementById('progress-bar');

    function showVIPToast() {
        const t = document.getElementById('vip-toast');
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3500);
    }

    // Render Function
    const renderPage = num => {
        pageIsRendering = true;

        pdfDoc.getPage(num).then(page => {
            const viewport = page.getViewport({ scale, rotation });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderCtx = { canvasContext: ctx, viewport: viewport };

            page.render(renderCtx).promise.then(() => {
                pageIsRendering = false;
                if(pageNumIsPending !== null) {
                    renderPage(pageNumIsPending);
                    pageNumIsPending = null;
                }
            });

            document.getElementById('page-num').value = num;
            document.getElementById('zoom-val').textContent = Math.round(scale * 100) + '%';
            
            // Update Progress Bar
            let progress = (num / pdfDoc.numPages) * 100;
            progressBar.style.width = progress + '%';

            // Auto Save Checkpoint (VIP only)
            if (isVIP) {
                localStorage.setItem(CACHE_CHECKPOINT, num);
            }
        });
    };

    const queueRenderPage = num => {
        if(pageIsRendering) {
            pageNumIsPending = num;
        } else {
            renderPage(num);
        }
    };

    const showPrevPage = () => {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    };

    const showNextPage = () => {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    };

    const gotoPage = (e) => {
        let desired = parseInt(e.target.value);
        if(desired >= 1 && desired <= pdfDoc.numPages) {
            pageNum = desired;
            queueRenderPage(pageNum);
        }
    };

    const zoomIn = () => {
        if(scale >= 3.0) return;
        scale += 0.2;
        queueRenderPage(pageNum);
    };

    const zoomOut = () => {
        if(scale <= 0.6) return;
        scale -= 0.2;
        queueRenderPage(pageNum);
    };

    /* PREMIUM FEATURES */
    document.getElementById('btn-rotate').addEventListener('click', () => {
        if(!isVIP) return showVIPToast();
        rotation = (rotation + 90) % 360;
        queueRenderPage(pageNum);
    });

    document.getElementById('toggle-dark').addEventListener('click', () => {
        if(!isVIP) return showVIPToast();
        const container = document.getElementById('reader-container');
        container.classList.toggle('dark-mode');
        const icon = document.querySelector('#toggle-dark i');
        if(container.classList.contains('dark-mode')) {
            icon.classList.replace('fa-moon', 'fa-sun');
        } else {
            icon.classList.replace('fa-sun', 'fa-moon');
        }
    });

    // Bookmarks UI
    function renderBookmarksList() {
        const list = document.getElementById('bookmark-list');
        list.innerHTML = '';
        if(bookmarks.length === 0) {
            list.innerHTML = '<p style="padding:1rem;color:#64748b;text-align:center;">Chưa có trang nào được lưu.</p>';
            return;
        }
        bookmarks.sort((a,b) => a - b).forEach(page => {
            const div = document.createElement('div');
            div.className = 'bookmark-item';
            div.innerHTML = `<span style="font-weight:600;"><i class="fas fa-star" style="color:#f59e0b; margin-right:8px;"></i>Trang ${page}</span> <i class="fas fa-trash del-bookmark" data-page="${page}"></i>`;
            
            // Click to goto
            div.addEventListener('click', (e) => {
                if(e.target.classList.contains('del-bookmark')) return;
                pageNum = page;
                queueRenderPage(pageNum);
            });
            // Delete
            div.querySelector('.del-bookmark').addEventListener('click', () => {
                bookmarks = bookmarks.filter(p => p !== page);
                localStorage.setItem(CACHE_BOOKMARKS, JSON.stringify(bookmarks));
                renderBookmarksList();
            });
            list.appendChild(div);
        });
    }

    document.getElementById('toggle-sidebar').addEventListener('click', () => {
        if(!isVIP) return showVIPToast();
        document.getElementById('bookmark-sidebar').classList.add('open');
        renderBookmarksList();
    });

    document.getElementById('close-sidebar').addEventListener('click', () => {
        document.getElementById('bookmark-sidebar').classList.remove('open');
    });

    document.getElementById('btn-bookmark').addEventListener('click', () => {
        if(!isVIP) return showVIPToast();
        if(!bookmarks.includes(pageNum)) {
            bookmarks.push(pageNum);
            localStorage.setItem(CACHE_BOOKMARKS, JSON.stringify(bookmarks));
            
            // show temporary success in toast
            const t = document.getElementById('vip-toast');
            t.innerHTML = `<i class="fas fa-check"></i> Đã kẹp thẻ đánh dấu Trang ${pageNum}`;
            t.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            t.classList.add('show');
            setTimeout(() => {
                t.classList.remove('show');
                setTimeout(() => { // revert style
                    t.style.background = '';
                    t.innerHTML = '<i class="fas fa-crown"></i> Tính năng Thượng Lưu (VIP). Cần số dư Ví từ 1.000.000đ!';
                }, 400);
            }, 2500);
        }
    });

    // Boot PDF
    pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
        pdfDoc = pdfDoc_;
        document.getElementById('page-count').textContent = pdfDoc.numPages;
        document.getElementById('loading-overlay').style.display = 'none';

        // Checkpoint Restore (VIP)
        if(isVIP) {
            let savedNum = parseInt(localStorage.getItem(CACHE_CHECKPOINT));
            if(savedNum && savedNum >= 1 && savedNum <= pdfDoc.numPages) {
                pageNum = savedNum;
                // Notify user
                setTimeout(() => {
                    const t = document.getElementById('vip-toast');
                    t.innerHTML = `<i class="fas fa-history"></i> VIP CHÀO MỪNG: Khôi phục tự động Trang ${pageNum}`;
                    t.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
                    t.classList.add('show');
                    setTimeout(() => { t.classList.remove('show'); t.style.background = ''; t.innerHTML = '<i class="fas fa-crown"></i> Tính năng Thượng Lưu (VIP). Cần số dư Ví từ 1.000.000đ!'; }, 4000);
                }, 1000);
            }
        }
        
        renderPage(pageNum);
    }).catch(err => {
        document.getElementById('loading-text').textContent = "Lỗi: " + err.message;
        document.getElementById('loading-text').style.color = "#ef4444";
    });

    // Events standard
    document.getElementById('prev-page').addEventListener('click', showPrevPage);
    document.getElementById('next-page').addEventListener('click', showNextPage);
    document.getElementById('zoom-in').addEventListener('click', zoomIn);
    document.getElementById('zoom-out').addEventListener('click', zoomOut);
    document.getElementById('page-num').addEventListener('change', gotoPage);

    // Fullscreen
    document.getElementById('toggle-fullscreen').addEventListener('click', () => {
        const container = document.getElementById('reader-container');
        if (!document.fullscreenElement) {
            if (container.requestFullscreen) { container.requestFullscreen(); }
        } else {
            if (document.exitFullscreen) { document.exitFullscreen(); }
        }
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/member/read-book.blade.php ENDPATH**/ ?>