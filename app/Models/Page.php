<?php

namespace App\Models;

use App\Models\Concerns\HasMarkdown;
use App\Models\MediaUsage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, HasMarkdown;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getContentHtmlAttribute(): string
    {
        return $this->renderMarkdown($this->content);
    }

    public function mediaUsages()
    {
        return $this->morphMany(MediaUsage::class, 'model');
    }
}
