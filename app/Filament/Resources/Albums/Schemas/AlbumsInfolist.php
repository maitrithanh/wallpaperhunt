<?php

namespace App\Filament\Resources\Albums\Schemas;

use App\Models\Albums;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AlbumsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('thumbnail')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->numeric(),
                TextEntry::make('wallpaper_count')
                    ->numeric(),
                TextEntry::make('like_count')
                    ->numeric(),
                TextEntry::make('view_count')
                    ->numeric(),
                TextEntry::make('partner_id')
                    ->numeric(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Albums $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
