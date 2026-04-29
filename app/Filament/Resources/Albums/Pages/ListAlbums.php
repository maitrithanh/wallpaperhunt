<?php

namespace App\Filament\Resources\Albums\Pages;

use App\Filament\Resources\Albums\AlbumsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlbums extends ListRecords
{
    protected static string $resource = AlbumsResource::class;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
