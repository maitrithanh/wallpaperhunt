<?php

namespace App\Filament\Resources\Withdraws\Schemas;

use App\Models\Withdraw;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WithdrawInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('content'),
                TextEntry::make('note')
                    ->placeholder('-'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('fee')
                    ->numeric(),
                TextEntry::make('status')
                    ->numeric(),
                TextEntry::make('partner_id')
                    ->numeric(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Withdraw $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
