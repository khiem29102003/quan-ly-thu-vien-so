<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;
use App\Models\BookReservation;
use App\Models\ActivityLog;
use App\Observers\BookObserver;
use App\Observers\UserObserver;
use App\Observers\BorrowObserver;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Đăng ký ChatService
        $this->app->singleton(ChatService::class, function ($app) {
            return new ChatService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Đăng ký Observers cho Activity Logging
        Book::observe(BookObserver::class);
        User::observe(UserObserver::class);
        Borrow::observe(BorrowObserver::class);

        View::composer('*', function ($view) {
            $adminNavBadges = [
                'pending_reservations' => 0,
                'pending_topups' => 0,
                'pending_total' => 0,
                'latest_pending_reservation_id' => 0,
            ];

            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'librarian'], true)) {
                $pendingReservations = BookReservation::where('status', 'pending');

                $pendingReservationsCount = (clone $pendingReservations)->count();
                $latestReservationId = (int) ((clone $pendingReservations)->max('id') ?? 0);

                $pendingTopupsCount = ActivityLog::where('log_name', 'wallet_topups')
                    ->where('event', 'requested')
                    ->where('properties', 'like', '%"status":"pending"%')
                    ->count();

                $adminNavBadges = [
                    'pending_reservations' => $pendingReservationsCount,
                    'pending_topups' => $pendingTopupsCount,
                    'pending_total' => $pendingReservationsCount + $pendingTopupsCount,
                    'latest_pending_reservation_id' => $latestReservationId,
                ];
            }

            $view->with('adminNavBadges', $adminNavBadges);
        });
    }
}
