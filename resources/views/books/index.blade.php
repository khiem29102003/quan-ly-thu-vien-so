@extends('layout')

@section('title', 'Quản Lý Sách')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>📚 Quản Lý Sách</h2>
    <a href="/books/create" class="btn btn-primary">+ Thêm Sách Mới</a>
</div>

<div class="card" style="margin-bottom: 1rem; background: linear-gradient(135deg, rgba(6,182,212,0.08) 0%, rgba(8,145,178,0.06) 100%); border-left: 4px solid #06b6d4;">
    <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between; align-items: center;">
        <div style="color: #0f172a; font-weight: 600;">Nguồn sách, phí mượn và phí quá hạn đã được bật trong hệ thống.</div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <span style="background:#e0f2fe;color:#0c4a6e;padding:0.25rem 0.6rem;border-radius:999px;font-size:0.8rem;">📱 Có hỗ trợ sách số</span>
            <span style="background:#ecfccb;color:#3f6212;padding:0.25rem 0.6rem;border-radius:999px;font-size:0.8rem;">💳 Có phí mượn</span>
            <span style="background:#fee2e2;color:#991b1b;padding:0.25rem 0.6rem;border-radius:999px;font-size:0.8rem;">⚠️ Có phí quá hạn</span>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="margin: 0; font-size: 1.1rem; color: var(--gray-700);">🔍 Bộ Lọc Tìm Kiếm</h3>
        <button onclick="document.getElementById('filterForm').classList.toggle('hidden')" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
            <span id="filterToggle">Hiện Bộ Lọc</span>
        </button>
    </div>
    
    <form id="filterForm" action="/books" method="GET" class="hidden" style="transition: all 0.3s ease;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group" style="margin: 0;">
                <label for="search" style="font-size: 0.875rem; color: var(--gray-600);">Tìm kiếm</label>
                <input type="text" name="search" id="search" placeholder="Tên sách, tác giả, ISBN..." value="{{ request('search') }}" style="width: 100%;">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label for="category" style="font-size: 0.875rem; color: var(--gray-600);">Danh mục</label>
                <select name="category" id="category" style="width: 100%;">
                    <option value="">-- Tất cả --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @if (request('category') == $cat->id) selected @endif>
                            {{ $cat->name }} ({{ $cat->books_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label for="language" style="font-size: 0.875rem; color: var(--gray-600);">Ngôn ngữ</label>
                <select name="language" id="language" style="width: 100%;">
                    <option value="">-- Tất cả --</option>
                    @foreach ($languages as $lang)
                        <option value="{{ $lang }}" @if (request('language') == $lang) selected @endif>{{ $lang }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label for="rating" style="font-size: 0.875rem; color: var(--gray-600);">Đánh giá tối thiểu</label>
                <select name="rating" id="rating" style="width: 100%;">
                    <option value="">-- Tất cả --</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @if (request('rating') == $i) selected @endif>{{ $i }} ⭐ trở lên</option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label for="availability" style="font-size: 0.875rem; color: var(--gray-600);">Tình trạng</label>
                <select name="availability" id="availability" style="width: 100%;">
                    <option value="">-- Tất cả --</option>
                    <option value="available" @if (request('availability') == 'available') selected @endif>Còn sách</option>
                    <option value="unavailable" @if (request('availability') == 'unavailable') selected @endif>Hết sách</option>
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label for="year_from" style="font-size: 0.875rem; color: var(--gray-600);">Năm XB từ</label>
                <input type="number" name="year_from" id="year_from" placeholder="VD: 2000" value="{{ request('year_from') }}" style="width: 100%;" min="1900" max="{{ date('Y') }}">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label for="year_to" style="font-size: 0.875rem; color: var(--gray-600);">Năm XB đến</label>
                <input type="number" name="year_to" id="year_to" placeholder="VD: {{ date('Y') }}" value="{{ request('year_to') }}" style="width: 100%;" min="1900" max="{{ date('Y') }}">
            </div>
        </div>
        
        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Áp Dụng Lọc
            </button>
            <a href="/books" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Xóa Lọc
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="search-bar">
        <form action="/books/search" method="GET" style="display: flex; gap: 0.5rem; width: 100%;">
            <input type="text" name="q" placeholder="Tìm kiếm sách, tác giả..." required>
            <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
        </form>
    </div>

    @if ($books->count() > 0)
        <div class="book-grid">
            @foreach ($books as $book)
                <div class="book-card">
                    <div class="book-cover">
                        @if ($book->cover_image)
                            <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 4rem;">📖</span>
                        @endif
                    </div>
                    <div class="book-info">
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-author">by {{ $book->author }}</div>
                        <div style="color: #6b7280; font-size: 0.85rem; margin: 0.5rem 0;">
                            ISBN: {{ $book->isbn }}
                        </div>
                        <div style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.5rem;">
                            Category: {{ $book->category->name }}
                        </div>

                        <div style="display:flex; gap:0.35rem; flex-wrap:wrap; margin-bottom:0.5rem;">
                            @if ($book->is_digital)
                                <span style="background:#ede9fe;color:#5b21b6;padding:0.2rem 0.5rem;border-radius:999px;font-size:0.75rem;">📱 Sách số</span>
                            @else
                                <span style="background:#f1f5f9;color:#334155;padding:0.2rem 0.5rem;border-radius:999px;font-size:0.75rem;">📚 Sách giấy</span>
                            @endif
                            <span style="background:#e0f2fe;color:#0c4a6e;padding:0.2rem 0.5rem;border-radius:999px;font-size:0.75rem;">Nguồn: {{ $book->source_type ?? 'purchase' }}</span>
                        </div>

                        <div style="font-size:0.82rem; color:#334155; margin-bottom:0.2rem;">
                            💰 Phí mượn: <strong>{{ number_format($book->borrow_fee ?? 0) }} đ</strong>
                        </div>
                        <div style="font-size:0.82rem; color:#991b1b; margin-bottom:0.5rem;">
                            ⚠️ Quá hạn/ngày: <strong>{{ number_format($book->daily_late_fee ?? 5000) }} đ</strong>
                        </div>

                        @if (!empty($book->source_name))
                            <div style="font-size:0.78rem; color:#64748b; margin-bottom:0.6rem;">Nhà cung cấp: {{ $book->source_name }}</div>
                        @endif

                        <span class="book-status @if ($book->available_copies > 0) status-available @else status-unavailable @endif">
                            @if ($book->available_copies > 0)
                                ✓ Có sẵn ({{ $book->available_copies }})
                            @else
                                ✗ Hết
                            @endif
                        </span>
                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                            <a href="/books/{{ $book->id }}" class="btn btn-secondary" style="flex: 1; text-align: center; padding: 0.5rem;">Chi Tiết</a>
                            <a href="/books/{{ $book->id }}/edit" class="btn btn-warning" style="flex: 1; text-align: center; padding: 0.5rem;">Sửa</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            {{ $books->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Không tìm thấy sách nào</p>
    @endif
</div>
@endsection
