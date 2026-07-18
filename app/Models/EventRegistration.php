<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'name',
        'email',
        'phone',
        'status',
        'notes',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    const STATUSES = [
        'registered' => 'Registered',
        'attended' => 'Attended',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    public function scopeAttended($query)
    {
        return $query->where('status', 'attended');
    }
}
