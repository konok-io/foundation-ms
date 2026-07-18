<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'category_id',
        'payee_name',
        'member_id',
        'amount',
        'currency',
        'payment_method',
        'description',
        'date',
        'reference_no',
        'attachment',
        'approved_by',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateVoucherNo(): string
    {
        $year = date('Y');
        $prefix = 'EXP/' . $year . '/';
        
        $last = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) str_replace($prefix, '', $last->voucher_no)) + 1 : 1;
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function scopeBetween($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('date', [$dateFrom, $dateTo]);
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
