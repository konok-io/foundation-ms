<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'description',
        'album_type',
        'cover_image',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const ALBUM_TYPES = [
        'photo' => 'Photo Gallery',
        'video' => 'Video Gallery',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function photos()
    {
        return $this->hasMany(GalleryImage::class)->where('type', 'photo');
    }

    public function videos()
    {
        return $this->hasMany(GalleryImage::class)->where('type', 'video');
    }

    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('album_type', $type);
    }
}
