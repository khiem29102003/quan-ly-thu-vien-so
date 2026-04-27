<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class BookObserver
{
    private function logActivity($event, Book $book)
    {
        // Chỉ log khi có HTTP request (không log khi seeding)
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        // Get authenticated user safely
        $causerId = null;
        $causerType = null;
        try {
            if (auth()->check()) {
                $causerId = auth()->id();
                $causerType = get_class(auth()->user());
            }
        } catch (\Exception $e) {
            // Authentication not configured, continue as guest
        }

        ActivityLog::create([
            'log_name' => 'books',
            'description' => "Book {$event}: {$book->title}",
            'subject_id' => $book->id,
            'subject_type' => Book::class,
            'event' => $event,
            'causer_id' => $causerId,
            'causer_type' => $causerType,
            'properties' => $book->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function created(Book $book)
    {
        $this->logActivity('created', $book);
    }

    public function updated(Book $book)
    {
        $this->logActivity('updated', $book);
    }

    public function deleted(Book $book)
    {
        $this->logActivity('deleted', $book);
    }
}
