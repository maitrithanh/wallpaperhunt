<?php

namespace App\Filament\Widgets;

use App\Models\Photos;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopWallpapersWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected function getTableHeading(): ?string
    {
        return 'Top 5 Wallpaper được xem nhiều nhất';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Photos::query()->orderBy('view_count', 'desc')->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('src')
                    ->label('Ảnh')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên tác phẩm'),
                Tables\Columns\TextColumn::make('partner.full_name')
                    ->label('Tác giả')
                    ->default('Ẩn danh'),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Lượt xem')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('like_count')
                    ->label('Lượt thích')
                    ->badge()
                    ->color('danger'),
            ]);
    }
}
