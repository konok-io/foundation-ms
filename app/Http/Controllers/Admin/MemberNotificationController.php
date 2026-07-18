<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberNotification;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberNotificationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('notifications.view');

        $query = MemberNotification::with(['member', 'creator']);

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_read')) {
            $query->where('is_read', $request->is_read);
        }

        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        $notifications = $query->latest()->paginate(20);

        return view('admin.notifications.index', [
            'title' => 'Notifications',
            'page_title' => 'Member Notifications',
            'notifications' => $notifications,
        ]);
    }

    public function create()
    {
        $this->authorize('notifications.create');

        $members = Member::active()->orderBy('name')->get();

        return view('admin.notifications.create', [
            'title' => 'Send Notification',
            'page_title' => 'Send Notification to Member',
            'members' => $members,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('notifications.create');

        $request->validate([
            'member_id' => 'required|exists:members,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:' . implode(',', array_keys(MemberNotification::TYPES)),
        ]);

        MemberNotification::create([
            'member_id' => $request->member_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification sent successfully.');
    }

    public function sendBulk(Request $request)
    {
        $this->authorize('notifications.create');

        $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:' . implode(',', array_keys(MemberNotification::TYPES)),
        ]);

        foreach ($request->member_ids as $memberId) {
            MemberNotification::create([
                'member_id' => $memberId,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifications sent to ' . count($request->member_ids) . ' members.');
    }

    public function markAsRead(MemberNotification $notification)
    {
        $this->authorize('notifications.view');

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $this->authorize('notifications.view');

        MemberNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(MemberNotification $notification)
    {
        $this->authorize('notifications.delete');

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
