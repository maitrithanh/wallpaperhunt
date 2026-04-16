<?php

namespace App\Filament\Resources\Photos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PhotosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
                FileUpload::make('src')
                    ->directory('photos')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('album_id')
                    ->required()
                    ->numeric(),
                TextInput::make('partner_id')
                    ->required()
                    ->numeric(),
                TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
            ]);
    }
}
