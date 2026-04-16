<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Models\Photos;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PhotosInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('src'),
                TextEntry::make('status')
                    ->numeric(),
                TextEntry::make('album_id')
                    ->numeric(),
                TextEntry::make('like_count')
                    ->numeric(),
                TextEntry::make('view_count')
                    ->numeric(),
                TextEntry::make('partner_id')
                    ->numeric(),
                TextEntry::make('price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Photos $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
