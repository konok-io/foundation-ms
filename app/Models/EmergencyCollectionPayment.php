<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyCollectionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_collection_id',
        'member_id',
        'amount',
        'paid_amount',
        'due_amount',
        'paid_date',
        'status',
        'payment_method',
        'transaction_id',
        'receipt_no',
        'notes',
        'created_by',
        'paid_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'paid_date' => 'date',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'partial' => 'Partial',
        'paid' => 'Paid',
        'waived' => 'Waived',
        'exempted' => 'Exempted',
    ];

    const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'online' => 'Online Payment',
        'check' => 'Check',
        'other' => 'Other',
    ];

    public function emergencyCollection()
    {
        return $this->belongsTo(EmergencyCollection::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->amount - $this->paid_amount);
    }

    public static function generateReceiptNo(): string
    {
        $last = self::whereNotNull('receipt_no')
            ->orderBy('id', 'desc')
            ->first();

        if ($last && preg_match('/ECP-(\d+)/', $last->receipt_no, $matches)) {
            $next = (int) $matches[1] + 1;
        } else {
            $next = 1;
        }

        return 'ECP-' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
