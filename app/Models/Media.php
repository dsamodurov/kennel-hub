<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\MediaUsage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'original_name',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
    ];

    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function usages()
    {
        return $this->hasMany(MediaUsage::class);
    }
}
