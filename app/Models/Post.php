<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category_id',
        'is_published',
        'published_at',
        'description',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_published' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getReadingTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200);
        return $readingTime . 'min reading time';
    }

    protected static function booted()
    {
        static::saving(function ($post)
        {
            $post->description = Str::limit(strip_tags($post->content), 150);
        });
    }
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }
}
