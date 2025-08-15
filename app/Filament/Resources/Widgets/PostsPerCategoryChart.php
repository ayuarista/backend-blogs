<?php
namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PostsPerCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Posts per Category';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Category::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Posts per Category',
                    'data' => $data->pluck('posts_count'),
                    'backgroundColor' => [
                        '#3B82F6', // Blue
                        '#EF4444', // Red
                        '#10B981', // Green
                        '#F59E0B', // Yellow
                        '#8B5CF6', // Purple
                        '#06B6D4', // Cyan
                        '#F97316', // Orange
                    ],
                ],
            ],
            'labels' => $data->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}