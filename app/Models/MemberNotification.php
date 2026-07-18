<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
        'created_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    const TYPES = [
        'due_reminder' => 'Due Reminder',
        'payment_received' => 'Payment Received',
        'notice' => 'Notice',
        'event' => 'Event',
        'emergency' => 'Emergency',
        'birthday' => 'Birthday',
        'expiry' => 'Membership Expiry',
        'general' => 'General',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
