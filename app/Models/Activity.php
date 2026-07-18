<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'description',
        'description_bn',
        'activity_type',
        'start_date',
        'end_date',
        'location',
        'location_bn',
        'beneficiaries_count',
        'volunteers_count',
        'budget',
        'status',
        'image',
        'report',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'beneficiaries_count' => 'integer',
        'volunteers_count' => 'integer',
        'budget' => 'decimal:2',
    ];

    const ACTIVITY_TYPES = [
        'medical' => 'Medical Aid',
        'education' => 'Education Support',
        'disaster_relief' => 'Disaster Relief',
        'community' => 'Community Service',
        'environmental' => 'Environmental',
        'food' => 'Food Distribution',
        'housing' => 'Housing Support',
        'employment' => 'Employment Support',
        'other' => 'Other',
    ];

    const STATUSES = [
        'planned' => 'Planned',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['in_progress', 'planned']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getTypeAttribute(): string
    {
        return self::ACTIVITY_TYPES[$this->activity_type] ?? $this->activity_type;
    }

    public function getStatusTextAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'planned' => 'info',
            'in_progress' => 'warning',
            'completed' => 'success',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }
}
