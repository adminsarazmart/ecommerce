<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('causer');

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(30);

        $logNames = ActivityLog::select('log_name')->distinct()->pluck('log_name');

        return Inertia::render('Admin/Audit/Index', [
            'logs' => $logs,
            'logNames' => $logNames,
            'filters' => $request->only(['log_name', 'event', 'causer_id', 'date_from', 'date_to']),
        ]);
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['causer', 'subject']);

        return Inertia::render('Admin/Audit/Show', ['log' => $activityLog]);
    }

    public function loginHistory(Request $request)
    {
        $query = LoginHistory::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $histories = $query->latest()->paginate(30);

        return Inertia::render('Admin/Audit/LoginHistory', [
            'histories' => $histories,
            'filters' => $request->only(['user_id', 'date_from', 'date_to']),
        ]);
    }

    public function clearLogs()
    {
        try {
            $days = request('days', 90);

            ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

            activity()->causedBy(auth()->user())->log("Cleared activity logs older than {$days} days");

            return redirect()->back()->with('success', "Logs older than {$days} days cleared");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
