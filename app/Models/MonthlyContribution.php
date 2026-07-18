<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'year',
        'month',
        'amount',
        'paid_amount',
        'due_amount',
        'due_date',
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
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'partial' => 'Partial',
        'paid' => 'Paid',
        'overdue' => 'Overdue',
        'waived' => 'Waived',
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

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'pending')
                  ->where('due_date', '<', now());
            });
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getFormattedPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, ['pending', 'overdue']) && $this->due_date < now();
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

        if ($last && preg_match('/RCP-(\d+)/', $last->receipt_no, $matches)) {
            $next = (int) $matches[1] + 1;
        } else {
            $next = 1;
        }

        return 'RCP-' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
