<?php
namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;

class RecentPostsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Posts';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()->with('category')->latest()->limit(10)
            )
            ->columns([
                ImageColumn::make('image')
                    ->square()
                    ->size(60),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                BadgeColumn::make('is_published')
                    ->label('Status')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft')
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ]),

                TextColumn::make('published_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Not published'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Post $record): string => route('filament.admin.resources.posts.edit', $record)),

                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Post $record): string => route('blog.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
