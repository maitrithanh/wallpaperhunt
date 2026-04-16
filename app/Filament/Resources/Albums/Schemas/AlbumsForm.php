<?php

namespace App\Filament\Resources\Albums\Schemas;

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
                TextInput::make('thumbnail')
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(1),
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
