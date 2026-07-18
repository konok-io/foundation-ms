<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_no',
        'type',
        'member_id',
        'payment_id',
        'contribution_id',
        'emergency_payment_id',
        'donation_id',
        'amount',
        'currency',
        'payment_method',
        'paid_at',
        'purpose',
        'description',
        'qr_code',
        'is_printed',
        'printed_at',
        'is_emailed',
        'emailed_at',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'printed_at' => 'datetime',
        'emailed_at' => 'datetime',
        'is_printed' => 'boolean',
        'is_emailed' => 'boolean',
    ];

    const TYPES = [
        'monthly_contribution' => 'Monthly Contribution',
        'emergency_collection' => 'Emergency Collection',
        'donation' => 'Donation',
        'event_fee' => 'Event Fee',
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

    public function contribution()
    {
        return $this->belongsTo(MonthlyContribution::class, 'contribution_id');
    }

    public function emergencyPayment()
    {
        return $this->belongsTo(EmergencyCollectionPayment::class, 'emergency_payment_id');
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeBetween($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('paid_at', [$dateFrom, $dateTo]);
    }

    public function getVerificationUrlAttribute(): string
    {
        return route('receipt.verify', ['receipt_no' => $this->receipt_no]);
    }

    public static function generateReceiptNo(string $prefix = 'RCP'): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($last && preg_match('/' . $prefix . '-' . $year . '-(\d+)/', $last->receipt_no, $matches)) {
            $next = (int) $matches[1] + 1;
        } else {
            $next = 1;
        }

        return $prefix . '-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public static function findByReceiptNo(string $receiptNo): ?self
    {
        return self::where('receipt_no', $receiptNo)->first();
    }

    public static function verify(string $receiptNo): array
    {
        $receipt = self::findByReceiptNo($receiptNo);

        if (!$receipt) {
            return [
                'valid' => false,
                'message' => 'Receipt not found',
            ];
        }

        return [
            'valid' => true,
            'receipt' => $receipt->load(['member', 'creator']),
            'message' => 'Receipt verified successfully',
        ];
    }
}
