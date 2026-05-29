<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Auth\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id);

        $filter = $request->input('filter', 'all');
        if ($filter === 'unread') $query->whereNull('read_at');
        elseif ($filter === 'read') $query->whereNotNull('read_at');

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') $query->oldest();
        else $query->latest();

        $notifications = $query->paginate(15)->withQueryString();

        $totalCount  = Notification::where('user_id', $user->id)->count();
        $unreadCount = Notification::where('user_id', $user->id)->whereNull('read_at')->count();
        $readCount   = Notification::where('user_id', $user->id)->whereNotNull('read_at')->count();

        return view('member.common.notifications.index', compact('notifications', 'totalCount', 'unreadCount', 'readCount', 'filter', 'sort'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->update(['read_at' => now()]);
        return back()->with('success', 'تم تحديد الإشعار كمقروء.');
    }

    public function readAll()
    {
        Notification::where('user_id', Auth::id())->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->delete();
        return back()->with('success', 'تم حذف الإشعار.');
    }

    public function clear()
    {
        Notification::where('user_id', Auth::id())->delete();
        return back()->with('success', 'تم حذف جميع الإشعارات.');
    }
}
