<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\TableFilter;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 10;

        $query = ActivityLog::latest('id');

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $filters = TableFilter::apply($query, $request, [
            'method' => [
                'label' => 'Metode',
                'type' => 'select',
                'options' => [
                    'POST' => 'POST',
                    'PUT' => 'PUT',
                    'PATCH' => 'PATCH',
                    'DELETE' => 'DELETE',
                ],
            ],
            'user_role' => [
                'label' => 'Peran (Role)',
                'type' => 'select',
                'options' => [
                    'super_admin' => 'Super Admin',
                    'admin' => 'Admin',
                    'finance_admin' => 'Admin Keuangan',
                    'agent' => 'Agen',
                    'guest' => 'Guest',
                ],
            ],
            'date' => [
                'label' => 'Tanggal',
                'type' => 'date_range',
                'column' => 'created_at',
            ],
        ]);

        if ($perPages == 'all') {
            $logs = $query->take(100)->get();
        } else {
            $perPage = min(max(intval($perPages), 1), 100);
            $logs = $query->paginate($perPage)->withQueryString();
        }

        return view('cms.admin.activity_logs.index', compact('logs', 'filters'));
    }
}
