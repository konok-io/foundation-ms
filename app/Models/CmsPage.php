<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'slug',
        'content',
        'content_bn',
        'excerpt',
        'excerpt_bn',
        'image',
        'icon',
        'position',
        'status',
        'page_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public const PAGE_TYPES = [
        'about' => 'About Us',
        'mission' => 'Mission & Vision',
        'history' => 'History',
        'chairman' => 'Chairman Message',
        'secretary' => 'General Secretary Message',
        'contact' => 'Contact Info',
        'footer' => 'Footer Content',
        'team' => 'Team Members',
        'faq' => 'FAQ',
    ];

    public static function getAboutContent()
    {
        return static::where('page_type', 'about')->first();
    }

    public static function getMissionVision()
    {
        return static::where('page_type', 'mission')->first();
    }

    public static function getHistory()
    {
        return static::where('page_type', 'history')->first();
    }

    public static function getChairmanMessage()
    {
        return static::where('page_type', 'chairman')->first();
    }

    public static function getSecretaryMessage()
    {
        return static::where('page_type', 'secretary')->first();
    }

    public static function getFooterContent()
    {
        return static::where('page_type', 'footer')->first();
    }
}
