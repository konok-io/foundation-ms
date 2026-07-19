<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'member_id',
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'currency',
        'payment_method',
        'gateway',
        'gateway_transaction_id',
        'gateway_response',
        'status',
        'paid_at',
        'failure_reason',
        'refund_id',
        'refund_amount',
        'refund_reason',
        'refunded_at',
        'notes',
        'created_by',
    ];

    const TYPES = [
        'monthly_contribution' => 'Monthly Contribution',
        'emergency_collection' => 'Emergency Collection',
        'donation' => 'Donation',
        'event_fee' => 'Event Fee',
        'other' => 'Other',
    ];

    const GATEWAYS = [
        'stripe' => 'Stripe',
        'paypal' => 'PayPal',
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
        'partially_refunded' => 'Partially Refunded',
        'cancelled' => 'Cancelled',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsRefundableAttribute(): bool
    {
        return $this->status === 'completed' && is_null($this->refunded_at);
    }

    public static function generatePaymentId(): string
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }

    public static function generateReceiptNo(): string
    {
        $last = self::whereNotNull('receipt_no')
            ->where('status', 'completed')
            ->orderBy('id', 'desc')
            ->first();

        if ($last && preg_match('/RCP-(\d+)/', $last->receipt_no ?? '', $matches)) {
            $next = (int) $matches[1] + 1;
        } else {
            $next = 1;
        }

        return 'RCP-' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
