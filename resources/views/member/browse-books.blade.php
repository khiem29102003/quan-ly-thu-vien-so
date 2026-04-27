@extends('layout')

@section('title', 'Duyệt Sách - Thư Viện')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0; font-size: 1.75rem;">🔍 Duyệt & Đặt Sách</h2>
    <a href="/member/dashboard" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

@if (!empty($hasOverdueBorrow))
    <div style="margin-bottom: 1rem; background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; padding: 0.9rem 1rem; border-radius: 8px;">
        Bạn đang có sách quá hạn. Tạm thời không thể mượn/đặt thêm và quyền đọc sách số bị khóa cho tới khi xử lý quá hạn.
    </div>
@endif

<div style="margin-bottom: 1rem; background: #ecfeff; color: #0c4a6e; border-left: 4px solid #06b6d4; padding: 0.85rem 1rem; border-radius: 8px;">
    Quy trình: Bấm <strong>Đặt Sách</strong> để gửi yêu cầu mượn. Thủ thư/Admin sẽ duyệt yêu cầu, sau đó sách chuyển sang trạng thái <strong>đang mượn</strong> trong tài khoản của bạn.
</div>

<!-- Search & Filter Section -->
<div class="chart-container" style="margin-bottom: 1.5rem;">
    <h3 class="chart-title">🔎 Tìm Kiếm Sách</h3>
    <form action="/member/browse" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">Tên Sách</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập tên sách..." class="form-control" style="width: 100%;">
        </div>
        
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">Danh Mục</label>
            <select name="category" class="form-control" style="width: 100%;">
                <option value="">-- Tất cả danh mục --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->books_count }})
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">Ngôn Ngữ</label>
            <select name="language" class="form-control" style="width: 100%;">
                <option value="">-- Tất cả ngôn ngữ --</option>
                @foreach ($languages as $lang)
                    <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fas fa-search"></i> Tìm Kiếm
            </button>
            <a href="/member/browse" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">
                <i class="fas fa-redo"></i> Đặt Lại
            </a>
        </div>
    </form>
</div>

<!-- Books Grid -->
<div class="chart-container">
    <h3 class="chart-title">📚 Kết Quả Tìm Kiếm ({{ $books->total() }} sách)</h3>
    
    @if ($books->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            @foreach ($books as $book)
                <div style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                    <!-- Book Cover -->
                    <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; position: relative; overflow: hidden;">
                        @if($book->cover_image)
                            <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-book"></i>
                        @endif
                        @if (isset($userReservations) && $userReservations->contains('book_id', $book->id))
                            <span style="position: absolute; top: 0; right: 0; background: #ec4899; color: white; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 600;">❤️ ĐÃ ĐẶT</span>
                        @elseif (isset($userBorrows) && $userBorrows->contains('book_id', $book->id))
                            <span style="position: absolute; top: 0; right: 0; background: #3b82f6; color: white; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 600;">📖 MƯỢN</span>
                        @endif
                    </div>
                    
                    <!-- Book Info -->
                    <div style="padding: 1rem;">
                        <h4 style="margin: 0 0 0.5rem 0; font-size: 0.95rem; color: #1f2937; font-weight: 600; min-height: 2.2em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $book->title }}</h4>
                        <p style="margin: 0 0 0.75rem 0; font-size: 0.85rem; color: #6b7280;">Tác giả: <strong>{{ Str::limit($book->author, 20) }}</strong></p>
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #9ca3af;">{{ $book->category->name ?? 'N/A' }}</p>
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #334155;">Phí mượn: {{ number_format($book->borrow_fee ?? 0) }}đ</p>
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #334155;">Nguồn: {{ $book->source_name ?? 'Chưa cập nhật' }}</p>
                        
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
                            @if ($book->language)
                                <span style="background: #e0e7ff; color: #4338ca; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">🌐 {{ $book->language }}</span>
                            @endif
                            @if ($book->is_digital)
                                <span style="background: #ede9fe; color: #5b21b6; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">📱 Ebook</span>
                            @endif
                            @if ($book->available_copies > 0)
                                <span style="background: #d1fae5; color: #065f46; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">✓ Có sẵn ({{ $book->available_copies }})</span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">❌ Hết</span>
                            @endif
                        </div>
                        
                        <!-- Action Button -->
                        @if (isset($userBorrows) && $userBorrows->contains('book_id', $book->id))
                            <button class="btn btn-info" disabled style="width: 100%; padding: 0.6rem 0.8rem; font-size: 0.85rem;">
                                <i class="fas fa-book"></i> Đang Mượn
                            </button>
                        @elseif (isset($userReservations) && $userReservations->contains('book_id', $book->id))
                            <button class="btn btn-secondary" disabled style="width: 100%; padding: 0.6rem 0.8rem; font-size: 0.85rem;">
                                <i class="fas fa-heart"></i> Đã Đặt
                            </button>
                        @else
                            <form action="/member/reserve/{{ $book->id }}" method="POST" style="display: inline; width: 100%;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.6rem 0.8rem; font-size: 0.85rem;" @if (!empty($hasOverdueBorrow)) disabled @endif>
                                    <i class="fas fa-heart"></i> Đặt Sách
                                </button>
                            </form>
                        @endif
                        
                        <a href="/member/book/{{ $book->id }}" class="btn btn-link" style="width: 100%; margin-top: 0.5rem; padding: 0.5rem 0.8rem; font-size: 0.8rem; text-align: center; text-decoration: none; color: #0284c7;">
                            Xem Chi Tiết →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; padding: 1rem; flex-wrap: wrap;">
            @if ($books->onFirstPage())
                <span style="padding: 0.5rem 1rem; background: #f3f4f6; color: #9ca3af; border-radius: 4px; cursor: not-allowed;">← Trước</span>
            @else
                <a href="{{ $books->previousPageUrl() }}&search={{ request('search') }}&category={{ request('category') }}&language={{ request('language') }}" style="padding: 0.5rem 1rem; background: #06b6d4; color: white; border-radius: 4px; text-decoration: none;">← Trước</a>
            @endif
            
            <div style="display: flex; gap: 0.25rem;">
                @foreach ($books->getUrlRange(max(1, $books->currentPage() - 2), min($books->lastPage(), $books->currentPage() + 2)) as $page => $url)
                    @if ($page == $books->currentPage())
                        <a href="{{ $url }}&search={{ request('search') }}&category={{ request('category') }}&language={{ request('language') }}" style="padding: 0.5rem 0.8rem; background: #0891b2; color: white; border-radius: 4px; text-decoration: none; font-weight: 600;">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}&search={{ request('search') }}&category={{ request('category') }}&language={{ request('language') }}" style="padding: 0.5rem 0.8rem; background: #e5e7eb; color: #1f2937; border-radius: 4px; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach
            </div>
            
            @if ($books->hasMorePages())
                <a href="{{ $books->nextPageUrl() }}&search={{ request('search') }}&category={{ request('category') }}&language={{ request('language') }}" style="padding: 0.5rem 1rem; background: #06b6d4; color: white; border-radius: 4px; text-decoration: none;">Tiếp →</a>
            @else
                <span style="padding: 0.5rem 1rem; background: #f3f4f6; color: #9ca3af; border-radius: 4px; cursor: not-allowed;">Tiếp →</span>
            @endif
        </div>
    @else
        <div style="text-align: center; padding: 2rem;">
            <p style="font-size: 1.25rem; color: #6b7280; margin: 1rem 0;">📭 Không tìm thấy sách nào</p>
            <p style="color: #9ca3af; margin-bottom: 1rem;">Vui lòng thử lại với từ khóa khác</p>
            <a href="/member/browse" class="btn btn-primary">Đặt Lại Bộ Lọc</a>
        </div>
    @endif
</div>
@endsection
