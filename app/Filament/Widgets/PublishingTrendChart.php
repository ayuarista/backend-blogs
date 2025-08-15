<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PublishingTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Publishing Trend (Last 12 Months)';
    
    protected static ?int $sort = 3;
    
    protected function getData(): array
    {
        $data = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Post::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
            
            $data->push([
                'month' => $date->format('M Y'),
                'count' => $count
            ]);
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Posts Created',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
}