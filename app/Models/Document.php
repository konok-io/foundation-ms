<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'document_type',
        'title',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'is_verified',
        'verified_by',
        'verified_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'file_size' => 'integer',
    ];

    const DOCUMENT_TYPES = [
        'national_id' => 'National ID',
        'passport' => 'Passport',
        'iqama' => 'Iqama',
        'birth_certificate' => 'Birth Certificate',
        'driving_license' => 'Driving License',
        'certificate' => 'Certificate',
        'academic_certificate' => 'Academic Certificate',
        'professional_certificate' => 'Professional Certificate',
        'other' => 'Other',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('is_verified', false);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($this->file_size, 1024));
        return round($this->file_size / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('admin.documents.download', $this);
    }
}
