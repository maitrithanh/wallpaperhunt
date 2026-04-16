<?php

namespace App\Filament\Resources\Withdraws\Pages;

use App\Filament\Resources\Withdraws\WithdrawResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWithdraw extends ViewRecord
{
    protected static string $resource = WithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
