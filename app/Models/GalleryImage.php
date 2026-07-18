<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'title',
        'image_path',
        'thumbnail_path',
        'video_url',
        'type',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    const TYPES = [
        'photo' => 'Photo',
        'video' => 'Video',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function scopePhotos($query)
    {
        return $query->where('type', 'photo');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return $this->url;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->video_url && str_contains($this->video_url, 'youtube.com')) {
            $videoId = $this->extractYouTubeId($this->video_url);
            if ($videoId) {
                return 'https://www.youtube.com/embed/' . $videoId;
            }
        }
        return null;
    }

    protected function extractYouTubeId(string $url): ?string
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }
}
