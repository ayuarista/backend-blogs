<?php
namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class PopularTagsWidget extends Widget
{
    protected static string $view = 'filament.widgets.popular-tags';

    protected static ?int $sort = 4;

    public function getViewData(): array
    {
        // Get all tags from published posts
        $tags = Post::where('is_published', true)
            ->whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(10);

        return [
            'tags' => $tags,
        ];
    }
}