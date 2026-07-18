<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'code',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function generateCode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? ((int) str_replace('INC-', '', $last->code)) + 1 : 1;
        return 'INC-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
