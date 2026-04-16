<?php

namespace App\Filament\Resources\Photos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên ảnh')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Mô tả')
                    ->searchable(),
                TextColumn::make('src')
                    ->label('URL ảnh')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('album_id')
                    ->label('Bộ sưu tập')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('partner_id')
                    ->label('Người yêu cầu')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Giá')
                    ->money()
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
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
