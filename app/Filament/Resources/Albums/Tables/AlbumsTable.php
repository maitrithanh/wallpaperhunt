<?php

namespace App\Filament\Resources\Albums\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AlbumsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên album')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Mô tả')
                    ->searchable(),
                TextColumn::make('thumbnail')
                    ->label('Ảnh bìa')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wallpaper_count')
                    ->label('Số lượng wallpaper')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('like_count')
                    ->label('Số lượt thích')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label('Số lượt xem')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('partner_id')
                    ->label('ID đối tác')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
