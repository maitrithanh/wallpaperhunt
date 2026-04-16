<?php

namespace App\Filament\Resources\Albums;

use App\Filament\Resources\Albums\Pages\CreateAlbums;
use App\Filament\Resources\Albums\Pages\EditAlbums;
use App\Filament\Resources\Albums\Pages\ListAlbums;
use App\Filament\Resources\Albums\Pages\ViewAlbums;
use App\Filament\Resources\Albums\Schemas\AlbumsForm;
use App\Filament\Resources\Albums\Schemas\AlbumsInfolist;
use App\Filament\Resources\Albums\Tables\AlbumsTable;
use App\Models\Albums;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AlbumsResource extends Resource
{
    protected static UnitEnum|string|null $navigationGroup = 'Quản lý nội dung';
    // protected static ?string $navigationLabel = 'Bộ sưu tập';
    protected static ?string $model = Albums::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Album';

    public static function form(Schema $schema): Schema
    {
        return AlbumsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AlbumsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlbumsTable::configure($table);
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
            'index' => ListAlbums::route('/'),
            'create' => CreateAlbums::route('/create'),
            'view' => ViewAlbums::route('/{record}'),
            'edit' => EditAlbums::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
