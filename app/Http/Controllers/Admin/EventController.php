<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('events.view');

        $query = Event::query();

        if ($request->has('status') && $request->status) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'past') {
                $query->past();
            }
        }

        if ($request->has('event_type') && $request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(15);

        return view('admin.events.index', [
            'title' => 'Events',
            'page_title' => 'Event Management',
            'events' => $events,
        ]);
    }

    public function create()
    {
        $this->authorize('events.create');

        return view('admin.events.create', [
            'title' => 'Create Event',
            'page_title' => 'Create New Event',
            'event' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('events.create');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:' . implode(',', array_keys(Event::EVENT_TYPES)),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'registration_required' => 'boolean',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
        ]);

        Event::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'description' => $request->description,
            'event_type' => $request->event_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'location_bn' => $request->location_bn,
            'max_attendees' => $request->max_attendees,
            'registration_required' => $request->registration_required ?? false,
            'registration_deadline' => $request->registration_deadline,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $this->authorize('events.view');

        $event->load(['creator', 'registrations.member']);

        $stats = [
            'total_registrations' => $event->registrations->count(),
            'attended' => $event->registrations->where('status', 'attended')->count(),
            'cancelled' => $event->registrations->where('status', 'cancelled')->count(),
        ];

        return view('admin.events.show', [
            'title' => 'Event Details',
            'page_title' => $event->title,
            'event' => $event,
            'stats' => $stats,
        ]);
    }

    public function edit(Event $event)
    {
        $this->authorize('events.edit');

        return view('admin.events.edit', [
            'title' => 'Edit Event',
            'page_title' => 'Edit: ' . $event->title,
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('events.edit');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:' . implode(',', array_keys(Event::EVENT_TYPES)),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'registration_required' => 'boolean',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
        ]);

        $event->update($request->only([
            'title', 'title_bn', 'description', 'event_type',
            'start_date', 'end_date', 'start_time', 'end_time',
            'location', 'location_bn', 'max_attendees',
            'registration_required', 'registration_deadline'
        ]));

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('events.delete');

        $event->registrations()->delete();
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function toggleStatus(Event $event)
    {
        $this->authorize('events.edit');

        $event->update(['is_active' => !$event->is_active]);

        return redirect()->back()->with('success', 'Event status updated.');
    }

    public function updateRegistration(Request $request, Event $event, EventRegistration $registration)
    {
        $this->authorize('events.edit');

        $request->validate([
            'status' => 'required|in:registered,attended,cancelled,no_show',
        ]);

        $registration->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Registration status updated.');
    }

    public function publicIndex()
    {
        $events = Event::active()
            ->upcoming()
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        return view('public.events.index', [
            'title' => 'Events',
            'events' => $events,
        ]);
    }

    public function publicShow(Event $event)
    {
        if (!$event->is_active) {
            abort(404);
        }

        $event->load('registrations');

        return view('public.events.show', [
            'title' => $event->title,
            'event' => $event,
        ]);
    }

    public function register(Request $request, Event $event)
    {
        if (!$event->registrationOpen()) {
            return redirect()->back()->with('error', 'Registration is closed for this event.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $memberId = auth()->check() ? auth()->user()->member?->id : null;

        EventRegistration::create([
            'event_id' => $event->id,
            'member_id' => $memberId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Registration successful!');
    }
}
