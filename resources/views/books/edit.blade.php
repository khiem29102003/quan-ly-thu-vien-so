@extends('layout')

@section('title', 'Sửa Sách')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/books" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 style="margin-bottom: 1.5rem;">✏️ Sửa Sách</h2>

    <form action="/books/{{ $book->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tên Sách *</label>
            <input type="text" name="title" id="title" required value="{{ $book->title }}">
            @error('title')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="author">Tác Giả *</label>
                <input type="text" name="author" id="author" required value="{{ $book->author }}">
                @error('author')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Mã số tiêu chuẩn quốc tế của sách (ISBN)</label>
                <input type="text" value="{{ $book->isbn }}" disabled>
                <small style="color: #6b7280;">Mã định danh sách dùng để quản lý và tra cứu, không thể thay đổi.</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Danh Mục *</label>
                <select name="category_id" id="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if ($book->category_id == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="total_copies">Tổng Bản Sao *</label>
                <input type="number" name="total_copies" id="total_copies" min="1" required value="{{ $book->total_copies }}">
                @error('total_copies')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="publisher">Nhà Xuất Bản</label>
                <input type="text" name="publisher" id="publisher" value="{{ $book->publisher }}">
            </div>

            <div class="form-group">
                <label for="publication_year">Năm Xuất Bản</label>
                <input type="number" name="publication_year" id="publication_year" min="1900" max="2099" value="{{ $book->publication_year }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="language">Ngôn Ngữ</label>
                <select name="language" id="language">
                    <option value="Tiếng Việt" @if ($book->language === 'Tiếng Việt') selected @endif>Tiếng Việt</option>
                    <option value="English" @if ($book->language === 'English') selected @endif>English</option>
                    <option value="Français" @if ($book->language === 'Français') selected @endif>Français</option>
                    <option value="日本語" @if ($book->language === '日本語') selected @endif>日本語</option>
                    <option value="한국어" @if ($book->language === '한국어') selected @endif>한국어</option>
                </select>
                @error('language')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="rating">Đánh Giá</label>
                <select name="rating" id="rating">
                    @for ($i = 0; $i <= 5; $i++)
                        <option value="{{ $i }}" @if ((int) $book->rating === $i) selected @endif>{{ $i }} ⭐</option>
                    @endfor
                </select>
                @error('rating')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
        </div>

        <h3 style="margin: 1.25rem 0 0.75rem;">Nguồn Sách Và Chi Phí</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="source_type">Nguồn Sách</label>
                <select name="source_type" id="source_type">
                    <option value="purchase" @if (($book->source_type ?? 'purchase') === 'purchase') selected @endif>Mua bản quyền</option>
                    <option value="donation" @if (($book->source_type ?? '') === 'donation') selected @endif>Tài trợ / tặng</option>
                    <option value="license" @if (($book->source_type ?? '') === 'license') selected @endif>Thuê license</option>
                    <option value="open-access" @if (($book->source_type ?? '') === 'open-access') selected @endif>Nguồn mở</option>
                    <option value="internal" @if (($book->source_type ?? '') === 'internal') selected @endif>Nội bộ</option>
                </select>
                @error('source_type')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="source_name">Nhà Cung Cấp / Nguồn</label>
                <input type="text" name="source_name" id="source_name" value="{{ old('source_name', $book->source_name) }}" placeholder="VD: Nhà xuất bản X, đối tác Y">
                @error('source_name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="source_url">Liên Kết Nguồn (nếu có)</label>
            <input type="url" name="source_url" id="source_url" value="{{ old('source_url', $book->source_url) }}" placeholder="https://...">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="borrow_fee">Phí Mượn (VND)</label>
                <input type="number" name="borrow_fee" id="borrow_fee" min="0" value="{{ old('borrow_fee', $book->borrow_fee ?? 0) }}">
            </div>

            <div class="form-group">
                <label for="daily_late_fee">Phí Quá Hạn / Ngày (VND)</label>
                <input type="number" name="daily_late_fee" id="daily_late_fee" min="0" value="{{ old('daily_late_fee', $book->daily_late_fee ?? 5000) }}">
            </div>
        </div>

        <h3 style="margin: 1.25rem 0 0.75rem;">Bản Sách Số</h3>

        <div class="form-group">
            <label style="display:flex; gap:0.5rem; align-items:center;">
                <input type="checkbox" name="is_digital" value="1" @if (old('is_digital', $book->is_digital)) checked @endif>
                Đây là sách số (ebook)
            </label>
            <small style="color: #6b7280;">Bỏ chọn sẽ xóa liên kết ebook cũ nếu không upload tệp mới.</small>
        </div>

        <div class="form-group">
            <label for="digital_file">Tệp Ebook (PDF)</label>
            <input type="file" name="digital_file" id="digital_file" accept="application/pdf">
            @if ($book->file_path)
                <div style="margin-top: 0.5rem; color: #0f766e;">Đã có tệp ebook: {{ basename($book->file_path) }}</div>
            @endif
            <small style="color: #6b7280;">Chỉ PDF, tối đa 20MB. Để trống nếu không đổi tệp.</small>
            <div id="digitalFileName" style="margin-top:0.35rem; color:#0f766e; font-size:0.85rem;"></div>
            @error('digital_file')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="cover_image">Ảnh Bìa Sách</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/*">
            @if ($book->cover_image)
                <div style="margin-top: 0.5rem;">
                    <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}" style="height: 120px; border-radius: 8px; object-fit: cover;">
                </div>
            @endif
            <small style="color: #6b7280;">Để trống nếu không đổi ảnh. JPG/PNG/WebP, tối đa 2MB.</small>
            <div id="coverFileName" style="margin-top:0.35rem; color:#0f766e; font-size:0.85rem;"></div>
            @error('cover_image')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea name="description" id="description" rows="4">{{ $book->description }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">💾 Lưu Thay Đổi</button>
            <a href="/books" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script>
    (function () {
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
    })();
</script>
@endsection
