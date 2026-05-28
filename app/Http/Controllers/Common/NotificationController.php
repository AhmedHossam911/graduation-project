<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

use App\Models\Auth\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Allows members and employees to manage their in-app notifications.
 * Provides functionality to view, filter, sort, read, and delete alerts.
 */
class NotificationController extends Controller
{
    /**
     * Display the notifications page.
     * Computes unread/read badges and applies dynamic filtering (by read status and time period) and sorting.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Notification::where('user_id', $user->id);

        // Filter by read status
        $filter = $request->input('filter', 'all');
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        // Filter by time period
        $period = $request->input('period', 'all');
        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek());
        } elseif ($period === 'month') {
            $query->where('created_at', '>=', Carbon::now()->startOfMonth());
        } elseif ($period === 'last30') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $notifications = $query->paginate(15)->withQueryString();

        // Counts for tab badges
        $totalCount  = Notification::where('user_id', $user->id)->count();
        $unreadCount = Notification::where('user_id', $user->id)->whereNull('read_at')->count();
        $readCount   = Notification::where('user_id', $user->id)->whereNotNull('read_at')->count();

        return view('common.notifications.index', compact(
            'notifications',
            'totalCount',
            'unreadCount',
            'readCount',
            'filter',
            'period',
            'sort'
        ));
    }

    /**
     * Mark a specific notification as read, ensuring it belongs to the authenticated user.
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return back()->with('success', 'تم تحديد الإشعار كمقروء.');
    }

    /**
     * Bulk action: Mark all currently unread notifications as read.
     */
    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }

    /**
     * Delete a specific notification record.
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'تم حذف الإشعار.');
    }

    /**
     * Bulk action: Completely clear (delete) the user's notification history.
     */
    public function clear()
    {
        Notification::where('user_id', Auth::id())->delete();

        return back()->with('success', 'تم حذف جميع الإشعارات.');
    }
}
