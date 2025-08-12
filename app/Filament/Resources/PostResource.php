<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Post Title')
                    ->placeholder('Enter the title of the post')
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->label('Post Slug')
                    ->placeholder('Enter the slug for the post')
                    ->unique(Post::class, 'slug', ignoreRecord: true),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')->required(),
                        Forms\Components\Textarea::make('description')
                    ]),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->label('Post Image')
                    ->maxSize(2048)
                    ->required()
                    ->helperText('max 2MB')
                    ->directory('blog-images'),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'h1',
                        'h3',
                        'bold',
                        'italic',
                        'underline',
                        'blockquote',
                        'bulletList',
                        'codeBlock',
                        'link',
                        'orderedList',
                        'numberedList',
                        'strike',
                        'undo',
                        'redo',
                    ]),
                Forms\Components\TagsInput::make('tags')
                    ->placeholder('Add tags')
                    ->separator(', '),
                Forms\Components\DateTimePicker::make('published_at')
                ->default(now()),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publish Post')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')
                ->square()
                ->size(50),
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable()
                ->limit(50),
            Tables\Columns\TextColumn::make('category.name')
                ->badge()
                ->searchable()
                ->sortable(),
            Tables\Columns\IconColumn::make('is_published')
            ->boolean()
            ->label('Published')
            ->trueIcon('heroicon-o-check-circle')
            ->falseIcon('heroicon-o-x-circle'),
            Tables\Columns\TextColumn::make('published_at')
                ->dateTime('M j, Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('M j, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category')
            ->relationship('category', 'name'),

            Tables\Filters\TernaryFilter::make('is_published')
                ->label('Published')
                ->boolean()
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
