<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Chi tiết Danh mục')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Tên danh mục')
                            ->weight('bold'),
                        TextEntry::make('slug')
                            ->label('Slug'),
                        TextEntry::make('description')
                            ->label('Mô tả')
                            ->placeholder('-'),
                        ImageEntry::make('avatar')
                            ->label('Ảnh đại diện')
                            ->disk('public')
                            ->height(150),
                    ]),
                \Filament\Schemas\Components\Section::make('Quản lý hệ thống')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->formatStateUsing(fn ($state) => \App\Models\Category::getStatusOptions()[$state] ?? $state)
                            ->color(fn ($state) => match ((int) $state) {
                                \App\Models\Category::STATUS_ACTIVE => 'success',
                                \App\Models\Category::STATUS_PENDING => 'warning',
                                \App\Models\Category::STATUS_INACTIVE => 'danger',
                                \App\Models\Category::STATUS_DRAFT => 'gray',
                                default => 'gray',
                            }),
                        TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Ngày cập nhật')
                            ->dateTime(),
                    ])
            ]);
    }
}
