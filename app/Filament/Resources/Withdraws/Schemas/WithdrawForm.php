<?php

namespace App\Filament\Resources\Withdraws\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WithdrawForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('content')
                    ->required(),
                TextInput::make('note')
                    ->default(null),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('partner_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
