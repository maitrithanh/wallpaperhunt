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
                    ->disk('public')
                    ->directory('photos')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(43008) // 42MB
                    ->formatStateUsing(function ($state) {
                        if (!$state || str_starts_with($state, 'http') || !\Illuminate\Support\Facades\Storage::disk('public')->exists($state)) {
                            return null;
                        }
                        return $state;
                    })
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options(\App\Models\Photos::getStatusOptions())
                    ->required()
                    ->default(\App\Models\Photos::STATUS_PENDING),
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
