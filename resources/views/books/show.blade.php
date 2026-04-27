@extends('layout')

@section('title', $book->title)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/books" style="color: #667eea; text-decoration: none;">← Quay lại</a>
</div>

<div class="card">
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 2rem;">
        <div style="border-radius: 8px; height: 300px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #22d3ee 100%);">
            @if ($book->cover_image)
                <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span style="color: white; font-size: 5rem;">📖</span>
            @endif
        </div>
        <div>
            <h2 style="margin-bottom: 0.5rem;">{{ $book->title }}</h2>
            <p style="color: #6b7280; font-size: 1.1rem; margin-bottom: 1rem;">by {{ $book->author }}</p>
            
            <table style="width: 100%; margin-bottom: 1.5rem;">
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Mã số tiêu chuẩn quốc tế của sách:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $book->isbn }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Danh Mục:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $book->category->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Nhà Xuất Bản:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $book->publisher ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Năm Xuất Bản:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $book->publication_year ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Ngôn Ngữ:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $book->language }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Tổng Bản Sao:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ $book->total_copies }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Sẵn Có:</strong></td>
                    <td style="padding: 0.5rem 0;">
                        <span class="book-status @if ($book->available_copies > 0) status-available @else status-unavailable @endif">
                            {{ $book->available_copies }} / {{ $book->total_copies }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Đánh Giá:</strong></td>
                    <td style="padding: 0.5rem 0; color: #f59e0b;">⭐ {{ $book->rating }}/5</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Nguồn Sách:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">
                        {{ $book->source_type ?? 'purchase' }}
                        @if ($book->source_name)
                            - {{ $book->source_name }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Phí Mượn:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ number_format($book->borrow_fee ?? 0) }} đ</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Phạt Quá Hạn / Ngày:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">{{ number_format($book->daily_late_fee ?? 5000) }} đ</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0;"><strong>Loại:</strong></td>
                    <td style="padding: 0.5rem 0; color: #6b7280;">
                        @if ($book->is_digital)
                            Sách số (ebook)
                        @else
                            Sách vật lý
                        @endif
                    </td>
                </tr>
            </table>

            <div style="margin-top: 1.5rem;">
                <a href="/books/{{ $book->id }}/edit" class="btn btn-warning">Chỉnh Sửa</a>
                <form action="/books/{{ $book->id }}" method="POST" style="display: inline; margin-left: 0.5rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                </form>
            </div>
        </div>
    </div>

    @if ($book->description)
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
            <h3 style="margin-bottom: 1rem;">📝 Mô Tả</h3>
            <p style="color: #374151; line-height: 1.6;">{{ $book->description }}</p>
        </div>
    @endif
</div>
@endsection
