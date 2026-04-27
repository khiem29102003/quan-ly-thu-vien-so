<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    private function logActivity($event, User $user)
    {
        // Chỉ log khi có HTTP request (không log khi seeding)
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        $properties = $user->toArray();
        unset($properties['password']); // Không log password

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
            'log_name' => 'users',
            'description' => "User {$event}: {$user->name}",
            'subject_id' => $user->id,
            'subject_type' => User::class,
            'event' => $event,
            'causer_id' => $causerId,
            'causer_type' => $causerType,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function created(User $user)
    {
        $this->logActivity('created', $user);
    }

    public function updated(User $user)
    {
        $this->logActivity('updated', $user);
    }

    public function deleted(User $user)
    {
        $this->logActivity('deleted', $user);
    }
}
