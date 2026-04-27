<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BookReservation;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BorrowController extends Controller
{
    private ?array $borrowColumns = null;
    private ?array $bookColumns = null;
    private ?array $userColumns = null;

    public function index(Request $request)
    {
        $query = Borrow::with('user', 'book');
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('borrowed_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('borrowed_at', '<=', $request->date_to);
        }
        
        if ($request->filled('overdue') && $request->overdue === '1') {
            $query->where('status', 'borrowed')
                  ->where('due_date', '<', now());
        }
        
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', "%{$search}%");
                });
            });
        }
        
        $borrows = $query->latest('borrowed_at')->paginate(15)->appends($request->except('page'));
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        
        return view('borrows.index', compact('borrows', 'users'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $books = Book::where('available_copies', '>', 0)->get();
        return view('borrows.create', compact('users', 'books'));
    }

    public function reservations(Request $request)
    {
        $query = BookReservation::with(['user', 'book'])->latest('reserved_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bq) use ($search) {
                    $bq->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            });
        }

        $reservations = $query->paginate(20)->appends($request->except('page'));

        return view('borrows.reservations', compact('reservations'));
    }

    public function approveReservation($reservationId)
    {
        $reservation = BookReservation::with(['user', 'book'])->findOrFail($reservationId);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Yeu cau dat sach nay khong con o trang thai cho duyet.');
        }

        $user = $reservation->user;
        $book = $reservation->book;

        if (!$user || !$book) {
            return back()->with('error', 'Khong tim thay thanh vien hoac sach lien quan.');
        }

        $hasOverdue = Borrow::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->exists();

        if ($hasOverdue) {
            return back()->with('error', 'Thanh vien dang co sach qua han, khong the duyet muon.');
        }

        if ((int) $book->available_copies <= 0) {
            return back()->with('error', 'Sach da het ban sao san co.');
        }

        $activeSameBook = Borrow::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($activeSameBook) {
            return back()->with('error', 'Thanh vien dang muon sach nay roi.');
        }

        $borrowColumns = $this->getBorrowColumns();
        $bookColumns = $this->getBookColumns();
        $userColumns = $this->getUserColumns();

        $hasBorrowFeeOnBook = in_array('borrow_fee', $bookColumns, true);
        $hasWalletBalance = in_array('wallet_balance', $userColumns, true);
        $hasBorrowFeeOnBorrow = in_array('borrow_fee', $borrowColumns, true);
        $borrowFee = $hasBorrowFeeOnBook ? (int) ($book->borrow_fee ?? 0) : 0;

        if ($borrowFee > 0 && $hasWalletBalance && (float) $user->wallet_balance < $borrowFee) {
            return back()->with('error', 'Vi thanh vien khong du so du de duyet muon sach nay.');
        }

        DB::transaction(function () use ($reservation, $book, $user, $borrowFee, $borrowColumns, $hasBorrowFeeOnBorrow, $hasWalletBalance) {
            $borrowPayload = [
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'borrowed',
            ];

            if ($hasBorrowFeeOnBorrow) {
                $borrowPayload['borrow_fee'] = $borrowFee;
            }

            $borrow = Borrow::create($this->filterByColumns($borrowPayload, $borrowColumns));

            if ($borrowFee > 0 && $hasWalletBalance) {
                $user->decrement('wallet_balance', $borrowFee);
            }

            $book->decrement('available_copies');
            $reservation->update(['status' => 'confirmed']);

            ActivityLog::create([
                'log_name' => 'reservations',
                'description' => 'Duyet yeu cau dat sach va tao phieu muon',
                'subject_id' => $reservation->id,
                'subject_type' => BookReservation::class,
                'event' => 'approved',
                'causer_id' => auth()->id(),
                'causer_type' => auth()->user() ? get_class(auth()->user()) : null,
                'properties' => [
                    'reservation_id' => $reservation->id,
                    'borrow_id' => $borrow->id,
                    'book_id' => $book->id,
                    'book_title' => $book->title,
                    'member_id' => $user->id,
                    'member_name' => $user->name,
                    'borrow_fee' => $borrowFee,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return back()->with('success', 'Da duyet yeu cau va tao phieu muon cho thanh vien.');
    }

    public function rejectReservation($reservationId)
    {
        $reservation = BookReservation::with(['user', 'book'])->findOrFail($reservationId);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Yeu cau dat sach nay da duoc xu ly truoc do.');
        }

        $reservation->update(['status' => 'cancelled']);

        ActivityLog::create([
            'log_name' => 'reservations',
            'description' => 'Tu choi yeu cau dat sach',
            'subject_id' => $reservation->id,
            'subject_type' => BookReservation::class,
            'event' => 'rejected',
            'causer_id' => auth()->id(),
            'causer_type' => auth()->user() ? get_class(auth()->user()) : null,
            'properties' => [
                'reservation_id' => $reservation->id,
                'book_id' => optional($reservation->book)->id,
                'book_title' => optional($reservation->book)->title,
                'member_id' => optional($reservation->user)->id,
                'member_name' => optional($reservation->user)->name,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Da tu choi yeu cau dat sach.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        $user = User::findOrFail($validated['user_id']);

        $borrowColumns = $this->getBorrowColumns();
        $bookColumns = $this->getBookColumns();
        $userColumns = $this->getUserColumns();

        $hasOverdue = Borrow::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->exists();

        if ($hasOverdue) {
            return back()->with('error', 'Người dùng đang có sách quá hạn, không thể mượn thêm!');
        }
        
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Sách không còn bản sao sẵn có!');
        }

        $hasBorrowFeeOnBook = in_array('borrow_fee', $bookColumns, true);
        $hasWalletBalance = in_array('wallet_balance', $userColumns, true);
        $hasBorrowFeeOnBorrow = in_array('borrow_fee', $borrowColumns, true);

        $borrowFee = $hasBorrowFeeOnBook ? (int) ($book->borrow_fee ?? 0) : 0;

        if ($borrowFee > 0 && $hasWalletBalance && (float) $user->wallet_balance < $borrowFee) {
            return back()->with('error', 'Số dư ví không đủ để mượn sách này.');
        }

        DB::transaction(function () use ($validated, $book, $user, $borrowFee, $borrowColumns, $hasWalletBalance, $hasBorrowFeeOnBorrow) {
            $borrowPayload = [
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'borrowed_at' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'borrowed',
            ];

            if ($hasBorrowFeeOnBorrow) {
                $borrowPayload['borrow_fee'] = $borrowFee;
            }

            Borrow::create($this->filterByColumns($borrowPayload, $borrowColumns));

            if ($borrowFee > 0 && $hasWalletBalance) {
                $user->decrement('wallet_balance', $borrowFee);
            }

            $book->decrement('available_copies');
        });
        
        return redirect()->route('borrows.index')->with('success', 'Tạo phiếu mượn thành công!');
    }

    public function returnBook($id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrowColumns = $this->getBorrowColumns();
        $userColumns = $this->getUserColumns();
        
        if ($borrow->status !== 'borrowed') {
            return back()->with('error', 'Phiếu mượn không hợp lệ!');
        }

        $isOverdue = now()->greaterThan($borrow->due_date);
        $lateDays = $isOverdue ? $borrow->due_date->diffInDays(now()) : 0;
        $hasDailyLateFee = in_array('daily_late_fee', $this->getBookColumns(), true);
        $lateFeePerDay = $hasDailyLateFee ? (int) ($borrow->book->daily_late_fee ?? 5000) : 0;
        $lateFee = $lateDays * $lateFeePerDay;

        DB::transaction(function () use ($borrow, $lateFee, $borrowColumns, $userColumns) {
            $user = $borrow->user;
            $hasWalletBalance = in_array('wallet_balance', $userColumns, true);
            $hasOutstandingDebt = in_array('outstanding_debt', $userColumns, true);

            $collected = 0;
            if ($lateFee > 0) {
                $wallet = $hasWalletBalance ? (float) ($user->wallet_balance ?? 0) : 0;
                $collected = (int) min($wallet, $lateFee);

                if ($collected > 0 && $hasWalletBalance) {
                    $user->decrement('wallet_balance', $collected);
                }

                $remaining = $lateFee - $collected;
                if ($remaining > 0 && $hasOutstandingDebt) {
                    $user->increment('outstanding_debt', $remaining);
                }
            }

            $updatePayload = [
                'returned_at' => now(),
                'status' => 'returned',
                'fine_amount' => $lateFee,
                'late_fee' => $lateFee,
                'late_fee_collected' => $collected,
            ];

            $borrow->update($this->filterByColumns($updatePayload, $borrowColumns));

            $borrow->book->increment('available_copies');
        });

        return redirect()->route('borrows.index')->with('success', 'Đã ghi nhận trả sách!');
    }

    public function destroy($id)
    {
        Borrow::findOrFail($id)->delete();
        return redirect()->route('borrows.index')->with('success', 'Xóa phiếu mượn thành công!');
    }

    private function getBorrowColumns(): array
    {
        if ($this->borrowColumns === null) {
            $this->borrowColumns = Schema::getColumnListing((new Borrow())->getTable());
        }

        return $this->borrowColumns;
    }

    private function getBookColumns(): array
    {
        if ($this->bookColumns === null) {
            $this->bookColumns = Schema::getColumnListing((new Book())->getTable());
        }

        return $this->bookColumns;
    }

    private function getUserColumns(): array
    {
        if ($this->userColumns === null) {
            $this->userColumns = Schema::getColumnListing((new User())->getTable());
        }

        return $this->userColumns;
    }

    private function filterByColumns(array $attributes, array $columns): array
    {
        return array_intersect_key($attributes, array_flip($columns));
    }
}
