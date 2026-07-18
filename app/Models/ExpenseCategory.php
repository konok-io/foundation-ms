<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
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

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function generateCode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? ((int) str_replace('EXP-', '', $last->code)) + 1 : 1;
        return 'EXP-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
