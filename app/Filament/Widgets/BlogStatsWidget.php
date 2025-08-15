<?php
namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;


class BlogStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count())
                ->description('All blog posts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Published Posts', Post::where('is_published', true)->count())
                ->description('Currently published')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Draft Posts', Post::where('is_published', false)->count())
                ->description('Unpublished drafts')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),

            Stat::make('Categories', Category::count())
                ->description('Total categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),
        ];
    }
}