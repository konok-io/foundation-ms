<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'voucher_no',
        'voucher_type',
        'account_type',
        'account_id',
        'description',
        'debit',
        'credit',
        'balance',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    const VOUCHER_TYPES = [
        'receipt' => 'Receipt',
        'payment' => 'Payment',
        'journal' => 'Journal',
        'contra' => 'Contra',
    ];

    const ACCOUNT_TYPES = [
        'income' => 'Income',
        'expense' => 'Expense',
        'asset' => 'Asset',
        'liability' => 'Liability',
        'equity' => 'Equity',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeBetween($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('date', [$dateFrom, $dateTo]);
    }

    public function scopeForAccount($query, $accountType, $accountId)
    {
        return $query->where('account_type', $accountType)->where('account_id', $accountId);
    }

    public function scopeIncome($query)
    {
        return $query->where('account_type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('account_type', 'expense');
    }
}
