<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['causer', 'subject'])->latest();

        // Filter by log name (books, users, borrows, reservations, wallet_topups)
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = ActivityLog::with(['causer', 'subject'])->findOrFail($id);
        return view('activity_logs.show', compact('log'));
    }
}
