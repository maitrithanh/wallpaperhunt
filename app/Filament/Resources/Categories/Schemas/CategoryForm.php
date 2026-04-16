<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
                FileUpload::make('avatar')
                    ->directory('categories')
                    ->default(null),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
                Select::make('status')
                    ->options(Category::getStatusOptions())
                    ->required()
                    ->default(Category::STATUS_PENDING),
            ]);
    }
}
