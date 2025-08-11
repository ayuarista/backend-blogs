<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'description' => $this->description ?? Str::limit(strip_tags($this->content), 150),
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'category' => $this->whenLoaded('category', function() {
                return[
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
            'tags' => $this->tags,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?-> format('Y-m-d H:i:s'),
            'reading_time' => $this->getReadingTime(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];


    }
    private function getReadingTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200);
        return $readingTime . ' min read';
    }
}