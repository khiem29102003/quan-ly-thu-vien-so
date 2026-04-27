<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SettingController;

// Public routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Book Routes (Admin/Librarian only)
Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class)->middleware('admin.or.librarian');
    Route::get('/books/search', [BookController::class, 'search'])->name('books.search')->middleware('admin.or.librarian');
    Route::get('/books/category/{id}', [BookController::class, 'filterByCategory'])->name('books.category')->middleware('admin.or.librarian');
    Route::get('/media/book-covers/{id}', [BookController::class, 'coverImage'])->name('books.cover');
});

// Borrow Routes (Admin/Librarian only)
Route::middleware('auth')->group(function () {
    Route::resource('borrows', BorrowController::class)->middleware('admin.or.librarian');
    Route::get('/borrow-requests', [BorrowController::class, 'reservations'])->name('borrows.reservations')->middleware('admin.or.librarian');
    Route::post('/borrow-requests/{reservationId}/approve', [BorrowController::class, 'approveReservation'])->name('borrows.reservations.approve')->middleware('admin.or.librarian');
    Route::post('/borrow-requests/{reservationId}/reject', [BorrowController::class, 'rejectReservation'])->name('borrows.reservations.reject')->middleware('admin.or.librarian');
    Route::post('/borrows/{id}/return', [BorrowController::class, 'returnBook'])->name('borrows.return')->middleware('admin.or.librarian');
    Route::post('/wallet-topups/{logId}/approve', [DashboardController::class, 'approveTopupRequest'])->name('wallet-topups.approve')->middleware('admin.or.librarian');
    Route::get('/wallet-topups', [DashboardController::class, 'topupRequests'])->name('wallet-topups.index')->middleware('admin.or.librarian');
    Route::get('/admin/realtime-notifications', [DashboardController::class, 'realtimeNotifications'])->name('admin.realtime-notifications')->middleware('admin.or.librarian');
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings')->middleware('admin.or.librarian');
    Route::post('/admin/settings', [SettingController::class, 'update'])->middleware('admin.or.librarian');
});

// User Routes (Admin/Librarian only)
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->middleware('admin.or.librarian');
});

// Activity Logs Routes (Admin/Librarian only)
Route::middleware('auth')->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index')->middleware('admin.or.librarian');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show')->middleware('admin.or.librarian');
});

// AI Routes
Route::get('/api/ai/recommendations/{userId}', [AIController::class, 'getRecommendations'])->name('ai.recommendations');
Route::post('/api/ai/search', [AIController::class, 'smartSearch'])->name('ai.search');
Route::get('/api/ai/trends', [AIController::class, 'analyzeReadingTrends'])->name('ai.trends');
Route::post('/api/ai/categorize', [AIController::class, 'categorizeBook'])->name('ai.categorize');

// Member Routes (Protected)
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/browse', [MemberController::class, 'browseBooks'])->name('member.browse');
    Route::get('/borrowed', [MemberController::class, 'currentBorrows'])->name('member.borrowed');
    Route::get('/book/{id}', [MemberController::class, 'viewBook'])->name('member.view-book');
    Route::get('/read/{borrowId}', [MemberController::class, 'readBorrowedBook'])->name('member.read-book');
    Route::get('/read/{borrowId}/stream', [MemberController::class, 'streamBorrowedBook'])->name('member.read-book.stream');
    Route::post('/reserve/{bookId}', [MemberController::class, 'reserveBook'])->name('member.reserve');
    Route::post('/return/{borrowId}', [MemberController::class, 'returnBorrowedBook'])->name('member.return-book');
    Route::post('/wallet/topup-request', [MemberController::class, 'requestTopup'])->name('member.wallet.topup-request');
    Route::post('/cancel-reservation/{reservationId}', [MemberController::class, 'cancelReservation'])->name('member.cancel-reservation');
    Route::get('/history', [MemberController::class, 'borrowHistory'])->name('member.history');
    Route::get('/profile', [MemberController::class, 'editProfile'])->name('member.profile');
    Route::put('/profile', [MemberController::class, 'updateProfile'])->name('member.profile.update');
});

// Test route for Chatbot API (only in development)
if (config('app.debug')) {
    Route::get('/test-chat-api', function () {
        return view('test-chat-api');
    })->name('test.chat.api');

    Route::get('/check-books', function () {
        $bookCount = \App\Models\Book::count();
        $categoryCount = \App\Models\Category::count();
        
        return response()->json([
            'total_books' => $bookCount,
            'total_categories' => $categoryCount,
            'books' => $bookCount > 0 ? \App\Models\Book::limit(5)->get() : [],
            'message' => $bookCount == 0 ? 'Không có sách! Cần chạy: php artisan db:seed' : 'OK'
        ]);
    })->name('check.books');

    Route::get('/test-database', function () {
        return view('test-database');
    })->name('test.database');
}
