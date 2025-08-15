<?php
namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class CategoryStatsWidget extends BaseWidget
{
    protected static ?string $heading = 'Categories Overview';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Category::query()
                    ->withCount(['posts', 'posts as published_posts_count' => function (Builder $query) {
                        $query->where('is_published', true);
                    }])
                    ->orderBy('posts_count', 'desc')
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('description')
                    ->limit(50)
                    ->placeholder('No description'),

                TextColumn::make('posts_count')
                    ->label('Total Posts')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('published_posts_count')
                    ->label('Published')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Category $record): string => route('filament.admin.resources.categories.edit', $record)),
            ]);
    }
}