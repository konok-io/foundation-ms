<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    const ACTIONS = [
        'create' => 'Created',
        'update' => 'Updated',
        'delete' => 'Deleted',
        'login' => 'Logged In',
        'logout' => 'Logged Out',
        'view' => 'Viewed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        if ($this->model_type) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForModel($query, $modelType, $modelId)
    {
        return $query->where('model_type', $modelType)->where('model_id', $modelId);
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public static function log($action, $model = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
