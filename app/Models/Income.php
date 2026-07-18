<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'category_id',
        'member_id',
        'amount',
        'currency',
        'payment_method',
        'received_from',
        'description',
        'date',
        'reference_no',
        'attachment',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateVoucherNo(): string
    {
        $year = date('Y');
        $prefix = 'INC/' . $year . '/';
        
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
