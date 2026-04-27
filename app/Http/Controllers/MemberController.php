<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReservation;
use App\Models\Borrow;
use App\Models\ActivityLog;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Member Dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $hasOverdueBorrow = $this->hasOverdueBorrow($user->id);
        
        $stats = [
            'active_borrows' => $user->borrows()->where('status', 'borrowed')->count(),
            'overdue_count' => $user->borrows()->where('status', 'borrowed')->where('due_date', '<', now())->count(),
            'total_borrowed' => $user->borrows()->count(),
            'reservations' => BookReservation::where('user_id', $user->id)->count(),
            'wallet_balance' => (float) $user->wallet_balance,
            'outstanding_debt' => (float) $user->outstanding_debt,
            'read_locked' => $hasOverdueBorrow,
        ];

        $activeBorrows = $user->borrows()->where('status', 'borrowed')->with('book')->latest('borrowed_at')->limit(5)->get();
        $reservations = BookReservation::where('user_id', $user->id)->with('book')->latest('reserved_at')->limit(8)->get();
        $topupRequests = ActivityLog::where('log_name', 'wallet_topups')
            ->where('causer_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $bankConfig = SystemSetting::getSetting('bank_config', [
            'bank_bin' => '970436', 
            'account_no' => '0987654321',
            'account_name' => 'THU VIEN HQT'
        ]);

        $momoConfig = SystemSetting::getSetting('momo_config', [
            'phone' => '0987.654.321',
            'name' => 'THƯ VIỆN HQT'
        ]);

        return view('member.dashboard', compact('stats', 'activeBorrows', 'reservations', 'topupRequests', 'bankConfig', 'momoConfig'));
    }

    // View all books to reserve
    public function browseBooks(Request $request)
    {
        $user = Auth::user();
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        $books = $query->paginate(12);
        $categories = \App\Models\Category::withCount('books')->having('books_count', '>', 0)->get();
        $languages = Book::distinct()->pluck('language')->filter();
        
        // Get user's reservations and active borrows
        $userReservations = BookReservation::where('user_id', $user->id)->where('status', 'pending')->get();
        $userBorrows = Borrow::where('user_id', $user->id)->where('status', 'borrowed')->get();
        $hasOverdueBorrow = $this->hasOverdueBorrow($user->id);

        return view('member.browse-books', compact('books', 'categories', 'languages', 'userReservations', 'userBorrows', 'hasOverdueBorrow'));
    }

    public function currentBorrows()
    {
        $user = Auth::user();

        $borrows = Borrow::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->latest('borrowed_at')
            ->paginate(15);

        $hasOverdueBorrow = $this->hasOverdueBorrow($user->id);

        return view('member.current-borrows', compact('borrows', 'hasOverdueBorrow'));
    }

    // View book details
    public function viewBook($id)
    {
        $book = Book::with('category')->findOrFail($id);
        $user = Auth::user();
        
        $isReserved = BookReservation::where('user_id', $user->id)
                                    ->where('book_id', $id)
                                    ->where('status', 'pending')
                                    ->exists();
        
        $userReservation = BookReservation::where('user_id', $user->id)
                                         ->where('book_id', $id)
                                         ->where('status', 'pending')
                                         ->first();
        
        $activeBorrow = Borrow::where('user_id', $user->id)
            ->where('book_id', $id)
            ->where('status', 'borrowed')
            ->latest('borrowed_at')
            ->first();

        $isBorrowed = $activeBorrow !== null;
        $hasOverdueBorrow = $this->hasOverdueBorrow($user->id);

        return view('member.book-detail', compact('book', 'isReserved', 'isBorrowed', 'userReservation', 'activeBorrow', 'hasOverdueBorrow'));
    }

    // Reserve a book
    public function reserveBook(Request $request, $bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        if ($this->hasOverdueBorrow($user->id)) {
            return back()->with('error', 'Bạn đang có sách quá hạn. Vui lòng xử lý quá hạn trước khi mượn/đặt thêm.');
        }

        // Check if already reserved
        $existingReservation = BookReservation::where('user_id', $user->id)
                                             ->where('book_id', $bookId)
                                             ->where('status', 'pending')
                                             ->first();

        if ($existingReservation) {
            return back()->with('error', 'Bạn đã đặt sách này rồi!');
        }

        // Check if already borrowing
        $activeBorrow = Borrow::where('user_id', $user->id)
                             ->where('book_id', $bookId)
                             ->where('status', 'borrowed')
                             ->first();

        if ($activeBorrow) {
            return back()->with('error', 'Bạn đang mượn sách này rồi!');
        }

        // Create reservation
        $reservation = BookReservation::create([
            'user_id' => $user->id,
            'book_id' => $bookId,
            'status' => 'pending',
            'reserved_at' => now(),
            'pickup_by' => now()->addDays(7)
        ]);

        ActivityLog::create([
            'log_name' => 'reservations',
            'description' => "Yeu cau dat sach moi: {$book->title}",
            'subject_id' => $reservation->id,
            'subject_type' => BookReservation::class,
            'event' => 'created',
            'causer_id' => $user->id,
            'causer_type' => get_class($user),
            'properties' => [
                'book_id' => $book->id,
                'book_title' => $book->title,
                'user_name' => $user->name,
                'status' => 'pending',
                'pickup_by' => $reservation->pickup_by,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Đặt sách thành công! Thư viện sẽ liên hệ với bạn sớm.');
    }

    // Cancel reservation
    public function cancelReservation($reservationId)
    {
        $reservation = BookReservation::findOrFail($reservationId);
        
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đặt sách này!');
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Đã hủy đặt sách!');
    }

    // View borrowing history
    public function borrowHistory()
    {
        $user = Auth::user();
        $borrows = $user->borrows()->with('book')->latest('borrowed_at')->paginate(15);
        
        $returnedCount = $user->borrows()->where('status', 'returned')->count();
        $overdueCount = $user->borrows()->where('status', 'borrowed')->where('due_date', '<', now())->count();
        $hasOverdueBorrow = $this->hasOverdueBorrow($user->id);

        return view('member.borrow-history', compact('borrows', 'returnedCount', 'overdueCount', 'hasOverdueBorrow'));
    }

    public function returnBorrowedBook($borrowId)
    {
        $user = Auth::user();

        $borrow = Borrow::with('book')
            ->where('id', $borrowId)
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->firstOrFail();

        $isOverdue = now()->greaterThan($borrow->due_date);
        $lateDays = $isOverdue ? $borrow->due_date->diffInDays(now()) : 0;
        $lateFeePerDay = (int) ($borrow->book->daily_late_fee ?? 5000);
        $lateFee = $lateDays * $lateFeePerDay;

        DB::transaction(function () use ($borrow, $user, $lateFee) {
            $walletBalanceExists = Schema::hasColumn('users', 'wallet_balance');
            $outstandingDebtExists = Schema::hasColumn('users', 'outstanding_debt');
            $borrowHasFineAmount = Schema::hasColumn('borrows', 'fine_amount');
            $borrowHasLateFee = Schema::hasColumn('borrows', 'late_fee');
            $borrowHasLateFeeCollected = Schema::hasColumn('borrows', 'late_fee_collected');

            $collected = 0;
            if ($lateFee > 0) {
                $wallet = $walletBalanceExists ? (float) ($user->wallet_balance ?? 0) : 0;
                $collected = (int) min($wallet, $lateFee);

                if ($collected > 0 && $walletBalanceExists) {
                    $user->decrement('wallet_balance', $collected);
                }

                $remaining = $lateFee - $collected;
                if ($remaining > 0 && $outstandingDebtExists) {
                    $user->increment('outstanding_debt', $remaining);
                }
            }

            $borrowPayload = [
                'returned_at' => now(),
                'status' => 'returned',
            ];

            if ($borrowHasFineAmount) {
                $borrowPayload['fine_amount'] = $lateFee;
            }

            if ($borrowHasLateFee) {
                $borrowPayload['late_fee'] = $lateFee;
            }

            if ($borrowHasLateFeeCollected) {
                $borrowPayload['late_fee_collected'] = $collected;
            }

            $borrow->update($borrowPayload);

            $borrow->book->increment('available_copies');

            ActivityLog::create([
                'log_name' => 'borrows',
                'description' => "Thanh vien tra sach: {$borrow->book->title}",
                'subject_id' => $borrow->id,
                'subject_type' => Borrow::class,
                'event' => 'member_returned',
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
                'properties' => [
                    'book_id' => $borrow->book_id,
                    'book_title' => $borrow->book->title,
                    'late_fee' => $lateFee,
                    'returned_at' => now(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        if ($lateFee > 0) {
            return back()->with('success', 'Tra sach thanh cong. Phi qua han da tinh: ' . number_format($lateFee) . ' VND.');
        }

        return back()->with('success', 'Tra sach thanh cong truoc/ dung han. Cam on ban!');
    }

    public function requestTopup(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'amount' => 'required|integer|min:10000|max:50000000',
            'note' => 'nullable|string|max:500',
        ]);

        ActivityLog::create([
            'log_name' => 'wallet_topups',
            'description' => 'Yeu cau nap vi tu thanh vien',
            'event' => 'requested',
            'causer_id' => $user->id,
            'causer_type' => get_class($user),
            'properties' => [
                'amount' => (int) $validated['amount'],
                'note' => $validated['note'] ?? null,
                'status' => 'pending',
                'requested_at' => now()->toDateTimeString(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Da gui yeu cau nap vi. Thu thu/Admin se duyet som.');
    }

    public function readBorrowedBook($borrowId)
    {
        $user = Auth::user();

        if ($this->hasOverdueBorrow($user->id)) {
            return redirect('/member/history')->with('error', 'Tài khoản đang có sách quá hạn. Quyền đọc bị khóa cho tới khi xử lý xong quá hạn.');
        }

        $borrow = Borrow::with('book')
            ->where('id', $borrowId)
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->firstOrFail();

        if (!$borrow->book->is_digital || empty($borrow->book->file_path)) {
            return redirect('/member/history')->with('error', 'Sách này không có bản đọc số.');
        }

        [$diskName, $path] = $this->resolveDigitalFileLocation($borrow->book->file_path);
        $exists = $diskName !== null;

        if (!$exists) {
            return redirect('/member/history')->with('error', 'Không tìm thấy tệp sách số. Vui lòng liên hệ thủ thư để cập nhật file ebook.');
        }

        return view('member.read-book', compact('borrow'));
    }

    public function streamBorrowedBook($borrowId)
    {
        $user = Auth::user();

        // Enforce overdue lock on every file request.
        if ($this->hasOverdueBorrow($user->id)) {
            abort(403, 'Tài khoản đang có sách quá hạn. Quyền đọc đang bị khóa.');
        }

        $borrow = Borrow::with('book')
            ->where('id', $borrowId)
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->firstOrFail();

        if (!$borrow->book->is_digital || empty($borrow->book->file_path)) {
            abort(404, 'Sách không có bản đọc số.');
        }

        [$diskName, $path] = $this->resolveDigitalFileLocation($borrow->book->file_path);
        if ($diskName === null || empty($path)) {
            abort(404, 'Không tìm thấy tệp ebook.');
        }

        $mime = Storage::disk($diskName)->mimeType($path) ?: 'application/pdf';
        $absolutePath = Storage::disk($diskName)->path($path);
        if (!is_file($absolutePath)) {
            abort(404, 'Không thể mở tệp ebook.');
        }

        $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $borrow->book->title) . '.pdf';

        return response()->file($absolutePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    }

    // Update profile
    public function editProfile()
    {
        $user = Auth::user();
        return view('member.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:10|max:20',
            'address' => 'nullable|string|max:500'
        ], [
            'name.required' => 'Tên là bắt buộc',
            'phone.required' => 'Số điện thoại là bắt buộc'
        ]);

        $user->update($request->only(['name', 'phone', 'address']));

        return redirect('/member/dashboard')->with('success', 'Cập nhật thông tin thành công!');
    }

    private function hasOverdueBorrow(int $userId): bool
    {
        return Borrow::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->exists();
    }

    private function resolveDigitalFileLocation(?string $rawPath): array
    {
        if (empty($rawPath)) {
            return [null, null];
        }

        $candidates = [$rawPath];

        // Handle legacy values like "public/private-books/abc.pdf" or "/storage/private-books/abc.pdf".
        $normalized = ltrim(str_replace('\\', '/', $rawPath), '/');
        $candidates[] = $normalized;
        if (str_starts_with($normalized, 'public/')) {
            $candidates[] = substr($normalized, 7);
        }
        if (str_starts_with($normalized, 'storage/')) {
            $candidates[] = substr($normalized, 8);
        }

        $basename = basename($normalized);
        if (!empty($basename)) {
            $candidates[] = 'private-books/' . $basename;
            $candidates[] = 'ebooks/' . $basename;
            $candidates[] = $basename;
        }

        foreach (array_unique($candidates) as $path) {
            if (Storage::disk('local')->exists($path)) {
                return ['local', $path];
            }

            if (Storage::disk('public')->exists($path)) {
                return ['public', $path];
            }
        }

        return [null, null];
    }
}
