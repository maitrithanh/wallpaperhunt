<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Ảnh đại diện')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('full_name')
                    ->label('Họ và tên')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('Số điện thoại')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->numeric()
                    ->sortable()
                    ->colors([
                        'success' => Customer::STATUS_ACTIVE,
                        'danger' => Customer::STATUS_INACTIVE,
                        'warning' => Customer::STATUS_SUSPENDED,
                        'secondary' => Customer::STATUS_DELETED,
                    ])
                    ->formatStateUsing(fn($state) => Customer::getStatusOptions()[$state]),
                TextColumn::make('email_verified_at')
                    ->label('Ngày xác minh email')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_login_at')
                    ->label('Ngày đăng nhập cuối')
                    ->dateTime()
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
