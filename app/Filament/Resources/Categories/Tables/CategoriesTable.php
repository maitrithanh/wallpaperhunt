<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable(),
                ImageColumn::make('avatar')
                    ->label('Ảnh đại diện')
                    ->disk('public'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Mô tả')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn ($state) => \App\Models\Category::getStatusOptions()[$state] ?? $state)
                    ->color(fn ($state) => match ((int) $state) {
                        \App\Models\Category::STATUS_ACTIVE => 'success',
                        \App\Models\Category::STATUS_PENDING => 'warning',
                        \App\Models\Category::STATUS_INACTIVE => 'danger',
                        \App\Models\Category::STATUS_DRAFT => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
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
            ->actions([
                \Filament\Actions\Action::make('approve')
                    ->label('Duyệt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (\App\Models\Category $record) => $record->update(['status' => \App\Models\Category::STATUS_ACTIVE]))
                    ->visible(fn (\App\Models\Category $record) => $record->status !== \App\Models\Category::STATUS_ACTIVE),
                \Filament\Actions\Action::make('reject')
                    ->label('Từ chối')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (\App\Models\Category $record) => $record->update(['status' => \App\Models\Category::STATUS_INACTIVE]))
                    ->visible(fn (\App\Models\Category $record) => $record->status !== \App\Models\Category::STATUS_INACTIVE),
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
