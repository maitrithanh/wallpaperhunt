<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('avatar')
                    ->default(null),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
