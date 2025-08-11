<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    // Custom view layout (optional)
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Post Details')
                    ->schema([
                        TextEntry::make('title')
                            ->size('lg')
                            ->weight('bold'),

                        TextEntry::make('slug')
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('category.name')
                            ->badge()
                            ->color('success'),

                        ImageEntry::make('image')
                            ->height(200),

                        TextEntry::make('content')
                            ->html()
                            ->prose(),

                        TextEntry::make('tags')
                            ->badge()
                            ->separator(','),

                        TextEntry::make('is_published')
                            ->badge()
                            ->color(fn ($state): string => match ((string) $state) {
                                '1' => 'success',
                                '0' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => $state ? 'Published' : 'Draft'),

                        TextEntry::make('published_at')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}