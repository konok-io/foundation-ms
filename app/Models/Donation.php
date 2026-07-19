<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'donor_name_bn',
        'email',
        'phone',
        'member_id',
        'amount',
        'currency',
        'purpose',
        'purpose_other',
        'payment_method',
        'gateway',
        'gateway_transaction_id',
        'payment_id',
        'status',
        'is_anonymous',
        'message',
        'received_at',
        'received_by',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'received_at' => 'datetime',
    ];

    const PURPOSES = [
        'general' => 'General Fund',
        'medical' => 'Medical Support',
        'education' => 'Education Support',
        'emergency' => 'Emergency Relief',
        'infrastructure' => 'Infrastructure Development',
        'other' => 'Other',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'refunded' => 'Refunded',
        'cancelled' => 'Cancelled',
    ];

    const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'online' => 'Online Payment',
        'check' => 'Check',
        'other' => 'Other',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForPurpose($query, $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeBetween($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('received_at', [$dateFrom, $dateTo]);
    }

    public function getPurposeLabelAttribute(): string
    {
        if ($this->purpose === 'other' && $this->purpose_other) {
            return $this->purpose_other;
        }
        return self::PURPOSES[$this->purpose] ?? $this->purpose;
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous Donor';
        }
        return $this->donor_name;
    }
}
