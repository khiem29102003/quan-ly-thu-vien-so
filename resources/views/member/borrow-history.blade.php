@extends('layout')

@section('title', 'Lịch Sử Mượn - Tài Khoản')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0; font-size: 1.75rem;">📚 Lịch Sử Mượn Sách</h2>
    <a href="/member/dashboard" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<div class="chart-container">
    <h3 class="chart-title">📖 Các Lần Mượn ({{ $borrows->total() }} tổng cộng)</h3>
    
    @if ($borrows->count() > 0)
        <!-- Desktop Table -->
        <div style="overflow-x: auto; margin-bottom: 1.5rem; display: none;" class="desktop-table">
            <table class="table" style="width: 100%; background: white;">
                <thead style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); color: white; font-weight: 600;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; border: none;">Tên Sách</th>
                        <th style="padding: 1rem; text-align: left; border: none;">Tác Giả</th>
                        <th style="padding: 1rem; text-align: center; border: none;">Ngày Mượn</th>
                        <th style="padding: 1rem; text-align: center; border: none;">Hạn Trả</th>
                        <th style="padding: 1rem; text-align: center; border: none;">Ngày Trả</th>
                        <th style="padding: 1rem; text-align: center; border: none;">Tình Trạng</th>
                        <th style="padding: 1rem; text-align: center; border: none;">Đọc</th>
                        <th style="padding: 1rem; text-align: center; border: none;">Trả</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrows as $borrow)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.3s ease;" onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                            <td style="padding: 1rem; border: none;">
                                <strong>{{ Str::limit($borrow->book->title, 40) }}</strong>
                            </td>
                            <td style="padding: 1rem; border: none;">
                                <span style="color: #6b7280;">{{ Str::limit($borrow->book->author, 30) }}</span>
                            </td>
                            <td style="padding: 1rem; text-align: center; border: none;">
                                <span class="badge" style="background: #e0e7ff; color: #4338ca; padding: 0.4rem 0.8rem; border-radius: 4px;">{{ $borrow->borrowed_at->format('d/m/Y') }}</span>
                            </td>
                            <td style="padding: 1rem; text-align: center; border: none;">
                                <span class="badge" style="background: #fef3c7; color: #92400e; padding: 0.4rem 0.8rem; border-radius: 4px;">{{ $borrow->due_date->format('d/m/Y') }}</span>
                            </td>
                            <td style="padding: 1rem; text-align: center; border: none;">
                                @if ($borrow->returned_at)
                                    <span class="badge" style="background: #d1fae5; color: #065f46; padding: 0.4rem 0.8rem; border-radius: 4px;">{{ $borrow->returned_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 0.4rem 0.8rem; border-radius: 4px;">—</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center; border: none;">
                                @if ($borrow->returned_at)
                                    <span style="background: #d1fae5; color: #065f46; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">✓ Đã Trả</span>
                                @elseif ($borrow->due_date < now())
                                    <span style="background: #fee2e2; color: #991b1b; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">⚠️ Quá Hạn</span>
                                @else
                                    <span style="background: #dbeafe; color: #0c4a6e; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">📖 Đang Mượn</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center; border: none;">
                                @if ($borrow->status === 'borrowed' && $borrow->book->is_digital)
                                    @if (!empty($hasOverdueBorrow))
                                        <span style="background:#fee2e2;color:#991b1b;padding:0.35rem 0.6rem;border-radius:4px;font-size:0.75rem;">Bị khóa</span>
                                    @else
                                        <a href="/member/read/{{ $borrow->id }}" class="btn btn-primary" style="padding:0.4rem 0.7rem;font-size:0.8rem;text-decoration:none;">Đọc</a>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center; border: none;">
                                @if ($borrow->status === 'borrowed')
                                    <form action="{{ route('member.return-book', $borrow->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" style="padding:0.4rem 0.7rem;font-size:0.8rem;" onclick="return confirm('Bạn muốn trả sách này ngay?')">Trả ngay</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (shown on small screens) -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach ($borrows as $borrow)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 0.25rem 0; color: #1f2937; font-weight: 600;">{{ Str::limit($borrow->book->title, 40) }}</h4>
                            <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">{{ Str::limit($borrow->book->author, 30) }}</p>
                        </div>
                        <span style="background: #f3f4f6; color: #374151; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600; white-space: nowrap;">
                            @if ($borrow->returned_at)
                                ✓ Đã Trả
                            @elseif ($borrow->due_date < now())
                                ⚠️ Quá Hạn
                            @else
                                📖 Mượn
                            @endif
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.85rem; color: #6b7280;">
                        <div>
                            <span style="font-weight: 600;">Ngày Mượn:</span><br>
                            <span style="color: #1f2937;">{{ $borrow->borrowed_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span style="font-weight: 600;">Hạn Trả:</span><br>
                            <span style="color: #1f2937;">{{ $borrow->due_date->format('d/m/Y') }}</span>
                        </div>
                        @if ($borrow->returned_at)
                            <div>
                                <span style="font-weight: 600;">Ngày Trả:</span><br>
                                <span style="color: #1f2937;">{{ $borrow->returned_at->format('d/m/Y') }}</span>
                            </div>
                        @else
                            <div>
                                <span style="font-weight: 600;">Trạng Thái:</span><br>
                                @if ($borrow->due_date < now())
                                    <span style="color: #991b1b; font-weight: 600;">⚠️ Quá Hạn</span>
                                @else
                                    <span style="color: #0c4a6e; font-weight: 600;">📖 Đang Mượn</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if ($borrow->status === 'borrowed' && $borrow->book->is_digital)
                        <div style="margin-top: 0.75rem;">
                            @if (!empty($hasOverdueBorrow))
                                <span style="background:#fee2e2;color:#991b1b;padding:0.35rem 0.6rem;border-radius:4px;font-size:0.8rem;">Bị khóa vì quá hạn</span>
                            @else
                                <a href="/member/read/{{ $borrow->id }}" class="btn btn-primary" style="padding:0.4rem 0.7rem;font-size:0.85rem;text-decoration:none;">Đọc ebook</a>
                            @endif
                        </div>
                    @endif

                    @if ($borrow->status === 'borrowed')
                        <div style="margin-top: 0.75rem;">
                            <form action="{{ route('member.return-book', $borrow->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" style="padding:0.4rem 0.7rem;font-size:0.85rem;" onclick="return confirm('Xác nhận trả sách này?')">Trả sách ngay</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; padding: 1.5rem; flex-wrap: wrap; margin-top: 1.5rem;">
            @if ($borrows->onFirstPage())
                <span style="padding: 0.5rem 1rem; background: #f3f4f6; color: #9ca3af; border-radius: 4px; cursor: not-allowed;">← Trước</span>
            @else
                <a href="{{ $borrows->previousPageUrl() }}" style="padding: 0.5rem 1rem; background: #06b6d4; color: white; border-radius: 4px; text-decoration: none;">← Trước</a>
            @endif
            
            <div style="display: flex; gap: 0.25rem;">
                @foreach ($borrows->getUrlRange(max(1, $borrows->currentPage() - 2), min($borrows->lastPage(), $borrows->currentPage() + 2)) as $page => $url)
                    @if ($page == $borrows->currentPage())
                        <a href="{{ $url }}" style="padding: 0.5rem 0.8rem; background: #0891b2; color: white; border-radius: 4px; text-decoration: none; font-weight: 600;">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" style="padding: 0.5rem 0.8rem; background: #e5e7eb; color: #1f2937; border-radius: 4px; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach
            </div>
            
            @if ($borrows->hasMorePages())
                <a href="{{ $borrows->nextPageUrl() }}" style="padding: 0.5rem 1rem; background: #06b6d4; color: white; border-radius: 4px; text-decoration: none;">Tiếp →</a>
            @else
                <span style="padding: 0.5rem 1rem; background: #f3f4f6; color: #9ca3af; border-radius: 4px; cursor: not-allowed;">Tiếp →</span>
            @endif
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem;">
            <p style="font-size: 1.25rem; color: #6b7280; margin: 1rem 0;">📭 Bạn chưa mượn sách nào</p>
            <p style="color: #9ca3af; margin-bottom: 1.5rem;">Hãy khám phá thư viện của chúng tôi và mượn sách yêu thích của bạn</p>
            <a href="/member/browse" class="btn btn-primary">
                <i class="fas fa-search"></i> Duyệt Sách Ngay
            </a>
        </div>
    @endif
</div>

<!-- Stats Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.95) 0%, rgba(8, 145, 178, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">📚</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;">{{ $borrows->total() }}</h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Tổng Mượn</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">✓</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;">{{ $returnedCount ?? 0 }}</h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Đã Trả</p>
        </div>
    </div>
    
    <div class="stat-card-premium glass-effect" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.95) 0%, rgba(217, 119, 6, 0.95) 100%); border: 1px solid rgba(255,255,255,0.2);">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.5rem;">
            <div style="font-size: 2rem;">⚠️</div>
            <h3 style="font-size: 1.5rem; margin: 0; color: white; font-weight: 700;">{{ $overdueCount ?? 0 }}</h3>
            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Quá Hạn</p>
        </div>
    </div>
</div>
@endsection
