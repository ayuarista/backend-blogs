<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function AfterCreate():void
    {
        Notification::make()
            ->success()
            ->title('Category Created Successfully')
            ->body('You can now add posts to this category.')
            ->send();
    }
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
