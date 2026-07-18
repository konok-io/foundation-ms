<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('activities.view');

        $query = Activity::query();

        if ($request->has('activity_type') && $request->activity_type) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $activities = $query->orderBy('start_date', 'desc')->paginate(12);

        return view('admin.activities.index', [
            'title' => 'Activities',
            'page_title' => 'Activity Management',
            'activities' => $activities,
        ]);
    }

    public function create()
    {
        $this->authorize('activities.create');

        return view('admin.activities.create', [
            'title' => 'Create Activity',
            'page_title' => 'Create New Activity',
            'activity' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('activities.create');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'activity_type' => 'required|in:' . implode(',', array_keys(Activity::ACTIVITY_TYPES)),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'volunteers_count' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Activity::STATUSES)),
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'title_bn', 'description', 'description_bn',
            'activity_type', 'start_date', 'end_date', 'location', 'location_bn',
            'beneficiaries_count', 'volunteers_count', 'budget', 'status'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $data['created_by'] = auth()->id();

        Activity::create($data);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        $this->authorize('activities.view');

        return view('admin.activities.show', [
            'title' => 'Activity Details',
            'page_title' => $activity->title,
            'activity' => $activity,
        ]);
    }

    public function edit(Activity $activity)
    {
        $this->authorize('activities.edit');

        return view('admin.activities.edit', [
            'title' => 'Edit Activity',
            'page_title' => 'Edit: ' . $activity->title,
            'activity' => $activity,
        ]);
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorize('activities.edit');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'activity_type' => 'required|in:' . implode(',', array_keys(Activity::ACTIVITY_TYPES)),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'volunteers_count' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Activity::STATUSES)),
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'title_bn', 'description', 'description_bn',
            'activity_type', 'start_date', 'end_date', 'location', 'location_bn',
            'beneficiaries_count', 'volunteers_count', 'budget', 'status'
        ]);

        if ($request->hasFile('image')) {
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($data);

        return redirect()->route('admin.activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $this->authorize('activities.delete');

        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity deleted successfully.');
    }

    public function updateStatus(Activity $activity, Request $request)
    {
        $this->authorize('activities.edit');

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Activity::STATUSES)),
        ]);

        $activity->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function publicIndex(Request $request)
    {
        $query = Activity::where('status', 'completed');

        if ($request->has('type') && $request->type) {
            $query->where('activity_type', $request->type);
        }

        $activities = $query->orderBy('start_date', 'desc')->paginate(6);

        return view('public.activities.index', [
            'title' => 'Our Activities',
            'activities' => $activities,
        ]);
    }

    public function publicShow(Activity $activity)
    {
        return view('public.activities.show', [
            'title' => $activity->title,
            'activity' => $activity,
        ]);
    }
}
