<?php

namespace App\Filament\Resources\Photos;

use App\Filament\Resources\Photos\Pages\CreatePhotos;
use App\Filament\Resources\Photos\Pages\EditPhotos;
use App\Filament\Resources\Photos\Pages\ListPhotos;
use App\Filament\Resources\Photos\Pages\ViewPhotos;
use App\Filament\Resources\Photos\Schemas\PhotosForm;
use App\Filament\Resources\Photos\Schemas\PhotosInfolist;
use App\Filament\Resources\Photos\Tables\PhotosTable;
use App\Models\Photos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PhotosResource extends Resource
{
    protected static UnitEnum|string|null $navigationGroup = 'Quản lý nội dung';
    protected static ?string $navigationLabel = 'Ảnh';
    protected static ?string $model = Photos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $recordTitleAttribute = 'Photo';

    public static function form(Schema $schema): Schema
    {
        return PhotosForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PhotosInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhotosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPhotos::route('/'),
            'create' => CreatePhotos::route('/create'),
            'view' => ViewPhotos::route('/{record}'),
            'edit' => EditPhotos::route('/{record}/edit'),
        ];
    }
}
