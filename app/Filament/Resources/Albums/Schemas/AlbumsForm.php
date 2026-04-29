<?php

namespace App\Filament\Resources\Albums\Schemas;

use App\Models\Albums;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AlbumsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
                FileUpload::make('thumbnail')
                    ->disk('public')
                    ->directory('AlbumsThumbnails'),
                Select::make('status')
                    ->options(Albums::getStatusOptions())
                    ->required()
                    ->default(Albums::STATUS_PENDING),
                TextInput::make('wallpaper_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('partner_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
