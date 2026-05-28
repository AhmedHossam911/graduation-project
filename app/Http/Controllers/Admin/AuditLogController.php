<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\AuditLog;
use Illuminate\Http\Request;

/**
 * Handles the administrative view of system-wide audit logs.
 * This controller allows Super Admins to monitor user actions and detect suspicious activity.
 */
class AuditLogController extends Controller
{
    /**
     * Display a paginated list of system audit logs.
     * Supports dynamic searching by Log ID, Action, or User Name.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $auditLogs = $query->latest()->paginate(10);

        return view('admin.auditlog.index', compact('auditLogs'));
    }
}
