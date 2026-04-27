<?php $__env->startSection('title', 'Thêm Sách Mới'); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom: 1.5rem;">
    <a href="/books" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 style="margin-bottom: 1.5rem;">📚 Thêm Sách Mới</h2>

    <form action="/books" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="title">Tên Sách *</label>
            <input type="text" name="title" id="title" required value="<?php echo e(old('title')); ?>">
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="author">Tác Giả *</label>
                <input type="text" name="author" id="author" required value="<?php echo e(old('author')); ?>">
                <?php $__errorArgs = ['author'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="isbn">Mã số tiêu chuẩn quốc tế của sách (ISBN) *</label>
                <input type="text" name="isbn" id="isbn" required value="<?php echo e(old('isbn')); ?>">
                <small style="color: #6b7280;">Mã định danh sách dùng để quản lý và tra cứu.</small>
                <?php $__errorArgs = ['isbn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Danh Mục *</label>
                <select name="category_id" id="category_id" required>
                    <option value="">-- Chọn Danh Mục --</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php if(old('category_id') == $category->id): ?> selected <?php endif; ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="total_copies">Tổng Bản Sao *</label>
                <input type="number" name="total_copies" id="total_copies" min="1" required value="<?php echo e(old('total_copies', 1)); ?>">
                <?php $__errorArgs = ['total_copies'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="publisher">Nhà Xuất Bản</label>
                <input type="text" name="publisher" id="publisher" value="<?php echo e(old('publisher')); ?>">
            </div>

            <div class="form-group">
                <label for="publication_year">Năm Xuất Bản</label>
                <input type="number" name="publication_year" id="publication_year" min="1900" max="2099" value="<?php echo e(old('publication_year')); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="language">Ngôn Ngữ</label>
                <select name="language" id="language">
                    <option value="Tiếng Việt" <?php if(old('language', 'Tiếng Việt') === 'Tiếng Việt'): ?> selected <?php endif; ?>>Tiếng Việt</option>
                    <option value="English" <?php if(old('language') === 'English'): ?> selected <?php endif; ?>>English</option>
                    <option value="Français" <?php if(old('language') === 'Français'): ?> selected <?php endif; ?>>Français</option>
                    <option value="日本語" <?php if(old('language') === '日本語'): ?> selected <?php endif; ?>>日本語</option>
                    <option value="한국어" <?php if(old('language') === '한국어'): ?> selected <?php endif; ?>>한국어</option>
                </select>
                <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="rating">Đánh Giá</label>
                <select name="rating" id="rating">
                    <?php for($i = 0; $i <= 5; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php if((string) old('rating', 0) === (string) $i): ?> selected <?php endif; ?>><?php echo e($i); ?> ⭐</option>
                    <?php endfor; ?>
                </select>
                <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <h3 style="margin: 1.25rem 0 0.75rem;">Nguồn Sách Và Chi Phí</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="source_type">Nguồn Sách</label>
                <select name="source_type" id="source_type">
                    <option value="purchase" <?php if(old('source_type', 'purchase') === 'purchase'): ?> selected <?php endif; ?>>Mua bản quyền</option>
                    <option value="donation" <?php if(old('source_type') === 'donation'): ?> selected <?php endif; ?>>Tài trợ / tặng</option>
                    <option value="license" <?php if(old('source_type') === 'license'): ?> selected <?php endif; ?>>Thuê license</option>
                    <option value="open-access" <?php if(old('source_type') === 'open-access'): ?> selected <?php endif; ?>>Nguồn mở</option>
                    <option value="internal" <?php if(old('source_type') === 'internal'): ?> selected <?php endif; ?>>Nội bộ</option>
                </select>
                <?php $__errorArgs = ['source_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="source_name">Nhà Cung Cấp / Nguồn</label>
                <input type="text" name="source_name" id="source_name" value="<?php echo e(old('source_name')); ?>" placeholder="VD: Nhà xuất bản X, đối tác Y">
                <?php $__errorArgs = ['source_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-group">
            <label for="source_url">Liên Kết Nguồn (nếu có)</label>
            <input type="url" name="source_url" id="source_url" value="<?php echo e(old('source_url')); ?>" placeholder="https://...">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="borrow_fee">Phí Mượn (VND)</label>
                <input type="number" name="borrow_fee" id="borrow_fee" min="0" value="<?php echo e(old('borrow_fee', 0)); ?>">
            </div>

            <div class="form-group">
                <label for="daily_late_fee">Phí Quá Hạn / Ngày (VND)</label>
                <input type="number" name="daily_late_fee" id="daily_late_fee" min="0" value="<?php echo e(old('daily_late_fee', 5000)); ?>">
            </div>
        </div>

        <h3 style="margin: 1.25rem 0 0.75rem;">Bản Sách Số</h3>

        <div class="form-group">
            <label style="display:flex; gap:0.5rem; align-items:center;">
                <input type="checkbox" name="is_digital" value="1" <?php if(old('is_digital')): ?> checked <?php endif; ?>>
                Đây là sách số (ebook)
            </label>
            <small style="color: #6b7280;">Khi là sách số, thành viên mượn có thể đọc online.</small>
        </div>

        <div class="form-group">
            <label for="digital_file">Tệp Ebook (PDF)</label>
            <input type="file" name="digital_file" id="digital_file" accept="application/pdf">
            <small style="color: #6b7280;">Chỉ PDF, tối đa 20MB.</small>
            <div id="digitalFileName" style="margin-top:0.35rem; color:#0f766e; font-size:0.85rem;"></div>
            <?php $__errorArgs = ['digital_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="cover_image">Ảnh Bìa Sách</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/*">
            <small style="color: #6b7280;">JPG/PNG/WebP, tối đa 2MB.</small>
            <div id="coverFileName" style="margin-top:0.35rem; color:#0f766e; font-size:0.85rem;"></div>
            <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: #ef4444; font-size: 0.85rem;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea name="description" id="description" rows="4"><?php echo e(old('description')); ?></textarea>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">💾 Lưu Sách</button>
            <a href="/books" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script>
    (function () {
        const form = document.querySelector('form[action="/books"]');
        const isDigital = document.querySelector('input[name="is_digital"]');
        const digitalFile = document.getElementById('digital_file');
        const coverFile = document.getElementById('cover_image');
        const digitalFileName = document.getElementById('digitalFileName');
        const coverFileName = document.getElementById('coverFileName');

        if (digitalFile) {
            digitalFile.addEventListener('change', function () {
                digitalFileName.textContent = this.files && this.files[0]
                    ? 'Đã chọn: ' + this.files[0].name
                    : '';
            });
        }

        if (coverFile) {
            coverFile.addEventListener('change', function () {
                coverFileName.textContent = this.files && this.files[0]
                    ? 'Đã chọn: ' + this.files[0].name
                    : '';
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                if (isDigital && isDigital.checked && (!digitalFile.files || digitalFile.files.length === 0)) {
                    e.preventDefault();
                    alert('Bạn đã bật sách số, vui lòng chọn tệp PDF trước khi lưu.');
                }
            });
        }
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\QLTV\resources\views/books/create.blade.php ENDPATH**/ ?>