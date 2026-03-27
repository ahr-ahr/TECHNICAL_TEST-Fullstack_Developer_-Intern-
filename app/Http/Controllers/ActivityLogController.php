<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::where('user_id', auth()->id());

        $actions = ActivityLog::select('action')
            ->distinct()
            ->pluck('action');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->action) {
            $query->where('action', $request->action);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $sort = $request->sort ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $logs = $query->paginate(10)->withQueryString();

        $groupedLogs = $logs->getCollection()->groupBy(function ($log) {
            return \Carbon\Carbon::parse($log->created_at)->format('Y-m-d');
        });

        return view('activity.index', compact('logs', 'groupedLogs', 'actions'));
    }
}
