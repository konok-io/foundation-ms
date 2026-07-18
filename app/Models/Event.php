<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'description',
        'description_bn',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'location_bn',
        'max_attendees',
        'registration_required',
        'registration_deadline',
        'banner',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'max_attendees' => 'integer',
        'registration_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    const EVENT_TYPES = [
        'meeting' => 'Meeting',
        'workshop' => 'Workshop',
        'seminar' => 'Seminar',
        'cultural' => 'Cultural Program',
        'sports' => 'Sports Event',
        'religious' => 'Religious Program',
        'volunteer' => 'Volunteer Activity',
        'fundraiser' => 'Fundraiser',
        'other' => 'Other',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees()
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'attended');
    }

    public function registeredAttendees()
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'registered');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
    }

    public function isUpcoming(): bool
    {
        return $this->start_date >= now()->toDateString();
    }

    public function isPast(): bool
    {
        return $this->end_date < now()->toDateString();
    }

    public function registrationOpen(): bool
    {
        if (!$this->registration_required) {
            return false;
        }
        if ($this->registration_deadline && $this->registration_deadline < now()->toDateString()) {
            return false;
        }
        if ($this->max_attendees && $this->registrations()->count() >= $this->max_attendees) {
            return false;
        }
        return true;
    }

    public function spotsLeft(): int
    {
        if (!$this->max_attendees) {
            return -1;
        }
        return max(0, $this->max_attendees - $this->registrations()->count());
    }
}
