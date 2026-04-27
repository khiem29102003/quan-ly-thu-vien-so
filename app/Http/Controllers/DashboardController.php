<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\BookReservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_borrows' => Borrow::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'active_borrows' => Borrow::where('status', 'borrowed')->count(),
            'overdue_borrows' => Borrow::where('status', 'overdue')->count(),
        ];

        $recent_borrows = Borrow::with('user', 'book')->latest()->limit(10)->get();
        $popular_books = Book::orderBy('rating', 'desc')->limit(10)->get();
        $categories = Category::withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->limit(10)
            ->get();

        // Biểu đồ: Mượn sách theo tháng (6 tháng gần nhất)
        $borrowsByMonth = Borrow::select(
            DB::raw('MONTH(borrowed_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('borrowed_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();

        // Biểu đồ: Sách theo danh mục
        $booksByCategory = Category::withCount('books')
            ->having('books_count', '>', 0)
            ->get()
            ->pluck('books_count', 'name')
            ->toArray();

        $pendingReservations = collect();
        $pendingTopupRequests = collect();

        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'librarian'], true)) {
            $pendingReservations = BookReservation::with(['user', 'book'])
                ->where('status', 'pending')
                ->latest('reserved_at')
                ->limit(8)
                ->get();

            $pendingTopupRequests = ActivityLog::with('causer')
                ->where('log_name', 'wallet_topups')
                ->where('event', 'requested')
                ->latest()
                ->limit(8)
                ->get()
                ->filter(function ($log) {
                    return data_get($log->properties, 'status', 'pending') === 'pending';
                })
                ->values();
        }

        return view('dashboard', compact('stats', 'recent_borrows', 'popular_books', 'categories', 'borrowsByMonth', 'booksByCategory', 'pendingReservations', 'pendingTopupRequests'));
    }

    public function approveTopupRequest(Request $request, $logId)
    {
        $staff = Auth::user();
        $log = ActivityLog::findOrFail($logId);

        if ($log->log_name !== 'wallet_topups' || $log->event !== 'requested') {
            return back()->with('error', 'Yeu cau nap vi khong hop le.');
        }

        if (data_get($log->properties, 'status', 'pending') !== 'pending') {
            return back()->with('error', 'Yeu cau nay da duoc xu ly truoc do.');
        }

        $amount = (int) data_get($log->properties, 'amount', 0);
        if ($amount <= 0) {
            return back()->with('error', 'So tien nap khong hop le.');
        }

        if (!Schema::hasColumn('users', 'wallet_balance')) {
            return back()->with('error', 'Bang users chua co cot wallet_balance. Vui long chay migrate.');
        }

        $member = User::findOrFail($log->causer_id);

        DB::transaction(function () use ($member, $log, $amount, $staff, $request) {
            $member->increment('wallet_balance', $amount);

            $properties = (array) ($log->properties ?? []);
            $properties['status'] = 'approved';
            $properties['approved_by'] = $staff->id;
            $properties['approved_by_name'] = $staff->name;
            $properties['approved_at'] = now()->toDateTimeString();
            $properties['final_wallet_balance'] = (float) $member->fresh()->wallet_balance;

            $log->update([
                'event' => 'approved',
                'description' => 'Da duyet yeu cau nap vi',
                'subject_id' => $member->id,
                'subject_type' => User::class,
                'properties' => $properties,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return back()->with('success', 'Da duyet nap vi ' . number_format($amount) . ' VND cho ' . $member->name . '.');
    }

    public function topupRequests(Request $request)
    {
        $query = ActivityLog::with('causer')
            ->where('log_name', 'wallet_topups')
            ->whereIn('event', ['requested', 'approved'])
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('event', 'requested');
            }

            if ($request->status === 'approved') {
                $query->where('event', 'approved');
            }
        }

        if ($request->filled('member')) {
            $search = trim($request->member);
            $query->whereHas('causer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $topupLogs = $query->paginate(20)->appends($request->except('page'));

        return view('wallet_topups.index', compact('topupLogs'));
    }

    public function realtimeNotifications()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'librarian'], true)) {
            abort(403);
        }

        $pendingReservations = BookReservation::with(['user', 'book'])
            ->where('status', 'pending')
            ->latest('reserved_at')
            ->limit(5)
            ->get();

        return response()->json([
            'latest_pending_reservation_id' => (int) ($pendingReservations->max('id') ?? 0),
            'pending_reservations_count' => $pendingReservations->count(),
            'pending_reservations' => $pendingReservations->map(function ($item) {
                return [
                    'id' => $item->id,
                    'book_title' => optional($item->book)->title,
                    'member_name' => optional($item->user)->name,
                    'reserved_at' => optional($item->reserved_at)->format('d/m/Y H:i'),
                ];
            })->values(),
        ]);
    }
}
