<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'content',
        'content_bn',
        'notice_type',
        'priority',
        'publish_date',
        'expire_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expire_date' => 'date',
        'is_active' => 'boolean',
    ];

    const NOTICE_TYPES = [
        'general' => 'General Notice',
        'meeting' => 'Meeting Notice',
        'emergency' => 'Emergency Notice',
        'event' => 'Event Notice',
        'holiday' => 'Holiday Notice',
        'member' => 'Member Notice',
    ];

    const PRIORITIES = [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('publish_date')
              ->orWhere('publish_date', '<=', now()->toDateString());
        });
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expire_date')
              ->orWhere('expire_date', '>=', now()->toDateString());
        });
    }

    public function scopeCurrent($query)
    {
        return $query->active()->published()->notExpired();
    }

    public function isExpired(): bool
    {
        if (!$this->expire_date) {
            return false;
        }
        return $this->expire_date < now()->toDateString();
    }

    public function isPublished(): bool
    {
        if (!$this->publish_date) {
            return true;
        }
        return $this->publish_date <= now()->toDateString();
    }
}
