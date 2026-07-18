<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('notices.view');

        $query = Notice::query();

        if ($request->has('notice_type') && $request->notice_type) {
            $query->where('notice_type', $request->notice_type);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $notices = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.notices.index', [
            'title' => 'Notices',
            'page_title' => 'Notice Management',
            'notices' => $notices,
        ]);
    }

    public function create()
    {
        $this->authorize('notices.create');

        return view('admin.notices.create', [
            'title' => 'Create Notice',
            'page_title' => 'Create New Notice',
            'notice' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('notices.create');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'notice_type' => 'required|in:' . implode(',', array_keys(Notice::NOTICE_TYPES)),
            'priority' => 'required|in:' . implode(',', array_keys(Notice::PRIORITIES)),
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:publish_date',
        ]);

        Notice::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'content' => $request->content,
            'content_bn' => $request->content_bn,
            'notice_type' => $request->notice_type,
            'priority' => $request->priority,
            'publish_date' => $request->publish_date,
            'expire_date' => $request->expire_date,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
    {
        $this->authorize('notices.view');

        return view('admin.notices.show', [
            'title' => 'Notice Details',
            'page_title' => $notice->title,
            'notice' => $notice,
        ]);
    }

    public function edit(Notice $notice)
    {
        $this->authorize('notices.edit');

        return view('admin.notices.edit', [
            'title' => 'Edit Notice',
            'page_title' => 'Edit: ' . $notice->title,
            'notice' => $notice,
        ]);
    }

    public function update(Request $request, Notice $notice)
    {
        $this->authorize('notices.edit');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'notice_type' => 'required|in:' . implode(',', array_keys(Notice::NOTICE_TYPES)),
            'priority' => 'required|in:' . implode(',', array_keys(Notice::PRIORITIES)),
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:publish_date',
        ]);

        $notice->update($request->only([
            'title', 'title_bn', 'content', 'content_bn',
            'notice_type', 'priority', 'publish_date', 'expire_date'
        ]));

        return redirect()->route('admin.notices.show', $notice)
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $this->authorize('notices.delete');

        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    public function toggleStatus(Notice $notice)
    {
        $this->authorize('notices.edit');

        $notice->update(['is_active' => !$notice->is_active]);

        return redirect()->back()->with('success', 'Notice status updated.');
    }

    public function publicIndex()
    {
        $notices = Notice::current()
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->orderBy('publish_date', 'desc')
            ->paginate(10);

        return view('public.notices.index', [
            'title' => 'Notices',
            'notices' => $notices,
        ]);
    }
}
