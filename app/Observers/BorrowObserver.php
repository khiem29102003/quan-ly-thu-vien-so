<?php

namespace App\Observers;

use App\Models\Borrow;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class BorrowObserver
{
    private function logActivity($event, Borrow $borrow)
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
            'log_name' => 'borrows',
            'description' => "Borrow {$event}: ID #{$borrow->id}",
            'subject_id' => $borrow->id,
            'subject_type' => Borrow::class,
            'event' => $event,
            'causer_id' => $causerId,
            'causer_type' => $causerType,
            'properties' => $borrow->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function created(Borrow $borrow)
    {
        $this->logActivity('created', $borrow);
    }

    public function updated(Borrow $borrow)
    {
        $this->logActivity('updated', $borrow);
    }

    public function deleted(Borrow $borrow)
    {
        $this->logActivity('deleted', $borrow);
    }
}
