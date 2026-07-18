<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'description',
        'description_bn',
        'type',
        'target_amount',
        'collected_amount',
        'amount_per_member',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'amount_per_member' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    const TYPES = [
        'medical' => 'Medical Emergency',
        'natural_disaster' => 'Natural Disaster',
        'funeral' => 'Funeral Support',
        'flood_relief' => 'Flood Relief',
        'earthquake' => 'Earthquake Relief',
        'fire_relief' => 'Fire Relief',
        'education' => 'Education Support',
        'other' => 'Other',
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'active' => 'Active',
        'closed' => 'Closed',
        'cancelled' => 'Cancelled',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(EmergencyCollectionPayment::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'emergency_collection_payments')
            ->withPivot(['amount', 'paid_amount', 'due_amount', 'paid_date', 'status', 'payment_method', 'notes'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 1));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->collected_amount);
    }

    public function getPaidCountAttribute(): int
    {
        return $this->payments()->where('status', 'paid')->count();
    }

    public function getUnpaidCountAttribute(): int
    {
        return $this->payments()->whereIn('status', ['pending', 'partial'])->count();
    }

    public function getTotalMembersAssignedAttribute(): int
    {
        return $this->payments()->count();
    }

    public function calculateCollectedAmount(): float
    {
        return $this->payments()->where('status', 'paid')->sum('paid_amount');
    }
}
