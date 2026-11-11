<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('method')) {
            $query->where('method', strtoupper($request->query('method')));
        }

        if ($request->filled('role')) {
            $query->where('user_role', $request->query('role'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->query('date'));
        }

        $logs = $query->latest('id')->paginate(20)->withQueryString();

        $filters = [
            'search' => $request->query('search'),
            'method' => $request->query('method'),
            'role' => $request->query('role'),
            'date' => $request->query('date'),
        ];

        return view('cms.admin.activity_logs.index', compact('logs', 'filters'));
    }
}
