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
                \Filament\Tables\Columns\ImageColumn::make('src')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->square()
                    ->size(60),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn ($state) => \App\Models\Photos::getStatusOptions()[$state] ?? $state)
                    ->color(fn ($state) => match ((int) $state) {
                        \App\Models\Photos::STATUS_PUBLIC => 'success',
                        \App\Models\Photos::STATUS_PENDING => 'warning',
                        \App\Models\Photos::STATUS_PRIVATE => 'gray',
                        \App\Models\Photos::STATUS_DEACTIVATED => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('album_id')
                    ->label('Bộ sưu tập')
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
            ->defaultSort('created_at', 'desc')
            ->actions([
                \Filament\Actions\Action::make('approve')
                    ->label('Duyệt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (\App\Models\Photos $record) {
                        $category = $record->category;
                        if ($category && $category->status !== \App\Models\Category::STATUS_ACTIVE) {
                            \Filament\Notifications\Notification::make()
                                ->title('Chưa thể duyệt ảnh')
                                ->body("Danh mục '{$category->name}' của ảnh này chưa được duyệt. Hãy duyệt danh mục trước!")
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->update(['status' => \App\Models\Photos::STATUS_PUBLIC]);
                        if ($record->partner) {
                            $customer = \App\Models\Customer::where('email', $record->partner->email)->first();
                            if ($customer) {
                                \App\Models\CustomerNotification::create([
                                    'customer_id' => $customer->id,
                                    'title' => 'Ảnh đã được duyệt',
                                    'message' => "Hình nền '{$record->name}' của bạn đã được phê duyệt và công khai.",
                                    'type' => 'approval',
                                    'is_read' => false
                                ]);
                            }
                        }
                    })
                    ->visible(fn (\App\Models\Photos $record) => $record->status !== \App\Models\Photos::STATUS_PUBLIC),
                \Filament\Actions\Action::make('reject')
                    ->label('Từ chối')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (\App\Models\Photos $record) {
                        $record->update(['status' => \App\Models\Photos::STATUS_DEACTIVATED]);
                        if ($record->partner) {
                            $customer = \App\Models\Customer::where('email', $record->partner->email)->first();
                            if ($customer) {
                                \App\Models\CustomerNotification::create([
                                    'customer_id' => $customer->id,
                                    'title' => 'Ảnh đã bị từ chối',
                                    'message' => "Hình nền '{$record->name}' của bạn không được phê duyệt.",
                                    'type' => 'rejection',
                                    'is_read' => false
                                ]);
                            }
                        }
                    })
                    ->visible(fn (\App\Models\Photos $record) => $record->status !== \App\Models\Photos::STATUS_DEACTIVATED),
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
