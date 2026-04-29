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
                    ->disk('public')
                    ->directory('categories')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(4096) // 4MB
                    ->formatStateUsing(function ($state) {
                        if (!$state || str_starts_with($state, 'http') || !\Illuminate\Support\Facades\Storage::disk('public')->exists($state)) {
                            return null;
                        }
                        return $state;
                    })
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
