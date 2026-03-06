<?php

namespace App\Models;

use App\Models\Concerns\HasMarkdown;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Media;
use App\Models\MediaUsage;

class News extends Model
{
    use HasFactory, SoftDeletes, HasMarkdown;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'cover_media_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'cover_media_id' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    public function scopeWithoutDate($query)
    {
        return $query->whereNull('published_at');
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return $this->renderMarkdown($this->description);
    }

    public function getContentHtmlAttribute(): string
    {
        return $this->renderMarkdown($this->content);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if ($this->coverMedia) {
            return $this->coverMedia->url;
        }
        return null;
    }

    public function coverMedia()
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function mediaUsages()
    {
        return $this->morphMany(MediaUsage::class, 'model');
    }
}
