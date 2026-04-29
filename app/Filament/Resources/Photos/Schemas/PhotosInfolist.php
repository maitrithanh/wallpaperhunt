<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Models\Photos;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PhotosInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Chi tiết Tác phẩm')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Tên tác phẩm')
                            ->weight('bold'),
                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->formatStateUsing(fn ($state) => \App\Models\Photos::getStatusOptions()[$state] ?? $state)
                            ->color(fn ($state) => match ((int) $state) {
                                \App\Models\Photos::STATUS_PUBLIC => 'success',
                                \App\Models\Photos::STATUS_PENDING => 'warning',
                                \App\Models\Photos::STATUS_PRIVATE => 'gray',
                                \App\Models\Photos::STATUS_DEACTIVATED => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('description')
                            ->label('Mô tả')
                            ->placeholder('-'),
                        \Filament\Infolists\Components\ImageEntry::make('src')
                            ->label('Hình ảnh preview')
                            ->disk('public')
                            ->height(300),
                    ]),
                
                \Filament\Schemas\Components\Section::make('Chỉ số tương tác')
                    ->schema([
                        TextEntry::make('view_count')
                            ->label('Lượt xem')
                            ->numeric(),
                        TextEntry::make('like_count')
                            ->label('Lượt thích')
                            ->numeric(),
                        TextEntry::make('price')
                            ->label('Giá')
                            ->money(),
                        TextEntry::make('created_at')
                            ->label('Ngày đăng')
                            ->dateTime(),
                    ])
            ]);
    }
}
